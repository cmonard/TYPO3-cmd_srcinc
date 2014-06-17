<?php

namespace CMD\CmdSrcinc;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Christophe Monard <contact@cmonard.fr>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *   52: class tx_cmdsrcinc
 *   70:    public function main($content, $conf)
 *  124:    private function setJS($content, $order)
 *  138:    private function setCSS($content, $order)
 *
 * TOTAL FUNCTIONS: 3
 *
 */

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3jquery'))
        require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3jquery', 'class.tx_t3jquery.php');

/**
 * Plugin 'Source inclusion' for the 'cmd_srcinc' extension.
 *
 * @author	Christophe Monard <contact@cmonard.fr>
 * @package	TYPO3
 * @subpackage	tx_cmdsrcinc
 */
class Inc extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin {

        public $prefixId = 'tx_cmdsrcinc';  // Same as class name
        public $scriptRelPath = 'Classes/Inc.php'; // Path to this script relative to the extension dir.
        public $extKey = 'cmd_srcinc'; // The extension key.
        public $pi_checkCHash = TRUE;
        protected $jsCounter = 10;
        protected $jsFooterCounter = 10;
        protected $csscounter = 1;
        protected $cssArray = array();

        /**
         * The main method of the PlugIn
         *
         * @param	string		$content: The PlugIn content
         * @param	array		$conf: The PlugIn configuration
         * @return	The content that is displayed on the website
         */
        public function main($content, $conf) {
                $allowedType = array('js', 'css');
                // get the active url to get and cache
                $activeList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $conf['activeList']);

                // then for each of them we get the cache or the url and push it to typo3
                foreach ($activeList as $el) {
                        // if conf exist we can do job
                        if (is_array($conf['src.'][$el . '.']) && count($conf['src.'][$el . '.']) > 0) {
                                foreach ($allowedType as $type) {
                                        if (isset($conf['src.'][$el . '.'][$type]) && $conf['src.'][$el . '.'][$type] == 1 && is_array($conf['src.'][$el . '.'][$type . '.'])) {
                                                $elConf = $conf['src.'][$el . '.'][$type . '.'];
                                                $cache = (!isset($elConf['cache']) || $elConf['cache'] == 1) ? TRUE : FALSE;
                                                $typeFunc = 'set' . strtoupper($type);
                                                $src = NULL;
                                                if ($cache) { // we can get it from typo3 cache - default 7 days
                                                        $src = \CMD\CmdApi\Api::getCache($el . $type, $this->extKey, ($elConf['cache_lifetime'] ? $elConf['cache_lifetime'] : 604800));
                                                        if ($src)
                                                                $src = unserialize($src);
                                                }
                                                if (!$src) { // if no data get it from the url
                                                        if (isset($elConf['subtype']) && $elConf['subtype'] == 'userfunc')
                                                                $src = \TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($elConf['url'], $elConf['url.'], $this);
                                                        elseif (isset($elConf['subtype']) && $elConf['subtype'] == 't3jquery')
                                                                $src = T3JQUERY === TRUE ? $this->cObj->fileResource(\tx_t3jquery::getJqJS(TRUE)) : '';
                                                        elseif (substr($elConf['url'], 0, 4) != 'http')
                                                                $src = $this->cObj->fileResource($elConf['url']);
                                                        else
                                                                $src = file_get_contents($elConf['url']); // get it from the web
                                                        if ($src !== FALSE && $cache)
                                                                \CMD\CmdApi\Api::setCache($el . $type, $this->extKey, $src); // set in cache if needed and data
                                                        if ($src === FALSE && isset($elConf['cache_lifetime']) && $elConf['cache_lifetime'] > 0) { // try to get src from cache if we cannot retrieve it
                                                                $src = \CMD\CmdApi\Api::getCache($el . $type, $this->extKey);
                                                                if ($src)
                                                                        $src = unserialize($src);
                                                        }
                                                }
                                                // we remove t3jquery from loading if needed
                                                if (isset($elConf['subtype']) && $elConf['subtype'] == 't3jquery' && T3JQUERY === TRUE)
                                                        unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess']['t3jquery']);
                                                // if exist, set it now!
                                                if ($src != '')
                                                        $this->$typeFunc($src, ($elConf['order'] ? $elConf['order'] : 0), ($elConf['toFooter'] && $elConf['toFooter'] == 1 ? TRUE : FALSE));
                                        }
                                }
                        }
                }
                // we merge all CSS with chr(10)
                if (count($this->cssArray)) {
                        ksort($this->cssArray);
                        $GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.']['_CSS_DEFAULT_STYLE'] = implode(chr(10), $this->cssArray);
                }
        }

        private function setJS($content, $order, $footer) {
                $f = $footer ? 'Footer' : '';
                $pos = 'js' . $f . 'Inline.';
                $var = 'js' . $f . 'Counter';
                if ($GLOBALS['TSFE']->pSetup[$pos])
                        while (isset($GLOBALS['TSFE']->pSetup[$pos][($order != 0 ? $order : $this->$var)]))
                                if ($order != 0)
                                        $order+=10;
                                else
                                        $this->$var+= 10;
                $GLOBALS['TSFE']->pSetup[$pos][($order != 0 ? $order : $this->$var)] = 'TEXT';
                $GLOBALS['TSFE']->pSetup[$pos][($order != 0 ? $order : $this->$var) . '.']['value'] = chr(10) . $content;
        }

        private function setCSS($content, $order, $dummyArg) {
                while (isset($this->cssArray[($order != 0 ? $order : $this->csscounter)]))
                        if ($order != 0)
                                $order++;
                        else
                                $this->csscounter++;
                $this->cssArray[($order != 0 ? $order : $this->csscounter)] = $content;
        }

}

?>
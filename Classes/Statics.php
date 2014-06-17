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
 *   47: class Statics
 *   53:    public function getImgResourcePostProcess($file, array $configuration, array $imageResource, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parent)
 *
 * TOTAL FUNCTIONS: 1
 *
 */

/**
 * Plugin 'Static Domain' for the 'cmd_srcinc' extension.
 *
 * @author	Christophe Monard <contact@cmonard.fr>
 * @package	TYPO3
 * @subpackage	tx_cmdsrcinc
 */
class Statics implements \TYPO3\CMS\Frontend\ContentObject\ContentObjectGetImageResourceHookInterface {

	/**
	 * Hook for post-processing image resources
	 *
	 * @param string $file Original image file
	 * @param array $configuration TypoScript getImgResource properties
	 * @param array $imageResource Information of the created/converted image resource
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parent Parent content object
	 * @return array Modified image resource information
	 */
	public function getImgResourcePostProcess($file, array $configuration, array $imageResource, \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $parent) {
                if ($GLOBALS['TSFE']->register['staticDomain'] || ($GLOBALS['TSFE']->tmpl->setup['config.']['staticDomain'] && $GLOBALS['TSFE']->tmpl->setup['config.']['staticDomain'] != '')) {
                        if (!$GLOBALS['TSFE']->register['staticDomain']) {
                                $static = $GLOBALS['TSFE']->tmpl->setup['config.']['staticDomain'];
                                if (substr($static, 0, 5) == 'http:')
                                        $static = substr($static, 5);
                                if (substr($static, 0, 2) != '//')
                                        $static = '//' . $static;
                                if (substr($static, -1) != '/')
                                        $static.= '/';
                                $GLOBALS['TSFE']->register['staticDomain'] = $static;
                        }
                        $imageResource[3] = $GLOBALS['TSFE']->register['staticDomain'] . $imageResource[3];
                }
                return $imageResource;
        }

}

?>
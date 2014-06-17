<?php
if (!defined('TYPO3_MODE')) die('Access denied.');

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'Classes/Inc.php', '', '', 1);
// handle static url for Image
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['getImgResource'][$_EXTKEY] = 'CMD\\CmdSrcinc\\Statics';
?>
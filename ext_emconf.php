<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cmd_srcinc".
 *
 * Auto generated 04-06-2013 15:30
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'CMD Source Include',
	'description' => 'This extension collect internal/external source file and include them internaly to typo3. This help to improve score of YSlow or GTMetrix, but help too to not depend on lower server/connexion to other server using TYPO3 cache',
	'category' => 'fe',
	'author' => 'Christophe Monard',
	'author_email' => 'contact@cmonard.fr',
	'shy' => '',
	'dependencies' => 'cmd_api',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'version' => '6.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.0.0-6.2.99',
			'php' => '5.3.3-5.4.99',
			'cmd_api' => '6.0.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'sourceopt' => '',
			'nc_staticfilecache' => '',
			't3jquery' => '-2.9.9',
		),
	),
	'suggests' => array(
	),
);

?>

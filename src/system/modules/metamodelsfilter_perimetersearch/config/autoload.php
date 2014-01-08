<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Metamodelsfilter_perimetersearch
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'TableMetaModelFilterSettingPerimetersearch' => 'system/modules/metamodelsfilter_perimetersearch/TableMetaModelFilterSettingPerimetersearch.php',
	'MetaModelFilterSettingPerimetersearch'      => 'system/modules/metamodelsfilter_perimetersearch/MetaModelFilterSettingPerimetersearch.php',
	'PerimetersearchLookUpContainer'             => 'system/modules/metamodelsfilter_perimetersearch/PerimetersearchLookUpContainer.php',
	'PerimetersearchLookUpGoogleMaps'            => 'system/modules/metamodelsfilter_perimetersearch/PerimetersearchLookUpGoogleMaps.php',
	'PerimetersearchLookUpInterface'             => 'system/modules/metamodelsfilter_perimetersearch/PerimetersearchLookUpInterface.php',
));

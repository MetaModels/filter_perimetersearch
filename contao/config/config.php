<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package	   MetaModels
 * @subpackage PerimeterSearch
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

/**
 * Frontend filter
 */

// Basic
$GLOBALS['METAMODELS']['filters']['perimetersearch']['class'] = 'MetaModelFilterSettingPerimetersearch';
$GLOBALS['METAMODELS']['filters']['perimetersearch']['image'] = 'system/modules/metamodelsfilter_perimetersearch/html/filter_perimetersearch.png';
$GLOBALS['METAMODELS']['filters']['perimetersearch']['info_callback'] = array('TableMetaModelFilterSetting', 'infoCallback');

// Supported attributes
$GLOBALS['METAMODELS']['filters']['perimetersearch']['attr_filter']['single'][] = 'geolocation';
$GLOBALS['METAMODELS']['filters']['perimetersearch']['attr_filter']['multi'][]  = 'text';
$GLOBALS['METAMODELS']['filters']['perimetersearch']['attr_filter']['multi'][]  = 'decimal';

// Plugins for georesolving
$GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'][] = 'PerimetersearchLookUpGoogleMaps';

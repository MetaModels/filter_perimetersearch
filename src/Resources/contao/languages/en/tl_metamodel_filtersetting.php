<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2024 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage FilterPerimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['geolocation_legend'] = 'Geolocation settings';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_legend']       = 'Range settings';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_legend']     = 'Country settings';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['fefilter_legend']    = 'Frontendfilter settings';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode']                = [
    'Data mode',
    'Here you can choose if you have one single attribute or two attributes.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['single_attr_id']          = [
    'Attribute',
    'Choose the attribute with the latitude and longitude values.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['first_attr_id']           = [
    'Attribute - Latitude',
    'Choose the attribute for the latitude values.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['second_attr_id']          = [
    'Attribute - Longitude',
    'Choose the attribute for the longitude values.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode']               = [
    'Range mode',
    'Here you can choose how the range will be displayed.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_preset']            = [
    'Range preset',
    'Here you can add a preset range.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection']         = [
    'Range selection',
    'Here you can add values.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection_default'] = [
    'Default',
    'Here you can set the default value.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice_service']   = [
    'LookUp services',
    'Here you can choose a look up service for resolving address data.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice_api_token'] = [
    'API token',
    'Here you can add a the API token.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_label']             = [
    'Range label',
    'Show range label instead of attribute name.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_placeholder']       = [
    'Range placeholder',
    'Show this text as long as the field is empty.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_template']          = [
    'Range template',
    'Sub template for this range filter element. Standard: form widget.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode']             = [
    'Country mode',
    'Here you can choose how the country will used.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_preset']          = [
    'Country preset',
    'Here you can add a preset for the country.'
];
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_get']             = [
    'GET-Parameter for country',
    'Here you can add the GET-Parameter name for the country lookup.'
];

/**
 * Options
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options']['single']     = 'Single Mode - One attribute';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options']['multi']      = 'Multi Mode - Two attributes';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options']['free']      = 'Free input mode';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options']['preset']    = 'Preset by system';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options']['selection'] = 'Selection mode';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options']['none']    = 'None';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options']['preset']  = 'Preset by system';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options']['get']     = 'Use GET-Param';

/**
 * Filter types
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typenames']['perimetersearch'] = 'Perimetersearch';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['_multicolumn_']    = '%s/%s';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['_multiname_']      = '%s"/"%s';

/**
 * Lookup names
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch']['coordinates']      = 'Coordinates input';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch']['google_maps']      = 'GoogleMaps Lookup';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch']['open_street_maps'] = 'OpenStreetMap Lookup';

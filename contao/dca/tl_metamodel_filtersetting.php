<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package       MetaModels
 * @subpackage    PerimeterSearch
 * @author        Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright     The MetaModels team.
 * @license       LGPL.
 * @filesource
 */

/**
 * Palettes
 */
// Default.
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+config'][]   = 'datamode';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+config'][]   = 'urlparam';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] = 'label';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] = 'template';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] = 'range_label';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] = 'range_template';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+range'][]    = 'rangemode';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+country'][]  = 'countrymode';

// Geolookup options.
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+geolocation'][] = 'lookupservice';

// Subpalettes.
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['datamode']['single']     = array('single_attr_id');
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['datamode']['multi']      = array
(
    'first_attr_id',
    'second_attr_id'
);
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['rangemode']['preset']    = array('range_preset');
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['rangemode']['selection'] = array('range_selection');
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['countrymode']['preset']  = array('country_preset');
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['countrymode']['get']     = array('country_get');

/**
 * Fields
 */

// ---- Land ----

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['countrymode'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('none', 'preset', 'get'),
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options'],
    'eval'      => array
    (
        'tl_class'       => 'w50 w50x',
        'doNotSaveEmpty' => true,
        'alwaysSave'     => true,
        'submitOnChange' => true,
        'mandatory'      => true,
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['country_preset'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_preset'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array
    (
        'tl_class'  => 'w50 w50x',
        'mandatory' => true
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['country_get'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_get'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array
    (
        'tl_class'  => 'w50 w50x',
        'mandatory' => true
    )
);

// ---- Range ----

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['rangemode'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('free', 'preset', 'selection'),
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options'],
    'eval'      => array
    (
        'tl_class'           => 'w50 w50x',
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_preset'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_preset'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array
    (
        'tl_class'  => 'w50 w50x',
        'mandatory' => true,
        'rgxp'      => 'digit'
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_selection'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => array
    (
        'tl_class'     => 'clr',
        'columnFields' => array
        (
            'range' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array
                (
                    'mandatory' => true,
                    'style'     => 'width:230px',
                    'rgxp'      => 'digit'
                )
            ),
        ),
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_label'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_label'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array
    (
        'tl_class' => 'clr w50',
    ),
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_template'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_template'],
    'default'   => 'mm_filteritem_default',
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'tl_class' => 'w50',
        'chosen'   => true
    ),
);

// ---- Data ----

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['datamode'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('single', 'multi'),
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options'],
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['lookupservice'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => array
    (
        'tl_class'     => 'w50 w50x',
        'columnFields' => array
        (
            'lookupservice' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice'],
                'exclude'   => true,
                'inputType' => 'select',
                'eval'      => array
                (
                    'includeBlankOption' => true,
                    'mandatory'          => true,
                    'chosen'             => true,
                    'style'              => 'width:250px',
                )
            ),
        ),
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['single_attr_id'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['single_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['first_attr_id'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['first_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['second_attr_id'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['second_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    )
);

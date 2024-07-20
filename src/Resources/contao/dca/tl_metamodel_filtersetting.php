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
 * @package    MetaModels/filter_perimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Christopher Bölter <christopher@boelter.eu>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Palettes
 */
// Default.
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+config'][]   =
    'datamode';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+config'][]   =
    'urlparam';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] =
    'label';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] =
    'placeholder';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] =
    'template';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] =
    'range_label';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+fefilter'][] =
    'range_template';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+range'][]    =
    'rangemode';
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+country'][]  =
    'countrymode';

// Geolookup options.
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['perimetersearch extends default']['+geolocation'][] =
    'lookupservice';

// Subpalettes.
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['datamode']['single']     =
    ['single_attr_id'];
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['datamode']['multi']      =
    [
        'first_attr_id',
        'second_attr_id'
    ];
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['rangemode']['preset']    =
    ['range_preset'];
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['rangemode']['selection'] =
    ['range_selection'];
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['rangemode']['free']      =
    ['range_placeholder'];
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['countrymode']['preset']  =
    ['country_preset'];
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metasubselectpalettes']['countrymode']['get']     =
    ['country_get'];

/*
 * Fields for land
 */

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['countrymode'] = [
    'label'       => 'countrymode.label',
    'description' => 'countrymode.description',
    'exclude'     => true,
    'inputType'   => 'select',
    'options'     => ['none', 'preset', 'get'],
    'reference'   => [
        'none'   => 'countrymode_options.none',
        'preset' => 'countrymode_options.preset',
        'get'    => 'countrymode_options.get'
    ],
    'eval'        => [
        'tl_class'       => 'w50 w50x',
        'doNotSaveEmpty' => true,
        'alwaysSave'     => true,
        'submitOnChange' => true,
        'mandatory'      => true
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['country_preset'] = [
    'label'       => 'country_preset.label',
    'description' => 'country_preset.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => [
        'tl_class'  => 'w50 w50x',
        'mandatory' => true
    ],
    'sql'         => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['country_get'] = [
    'label'       => 'country_get.label',
    'description' => 'country_get.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => [
        'tl_class'  => 'w50 w50x',
        'mandatory' => true
    ],
    'sql'         => 'text NULL'
];

/*
 * Fields for range
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['rangemode'] = [
    'label'       => 'rangemode.label',
    'description' => 'rangemode.description',
    'exclude'     => true,
    'inputType'   => 'select',
    'options'     => ['free', 'preset', 'selection'],
    'reference'   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options'],
    'eval'        => [
        'tl_class'           => 'w50 w50x',
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_preset'] = [
    'label'       => 'range_preset.label',
    'description' => 'range_preset.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => [
        'tl_class'  => 'w50 w50x',
        'mandatory' => true,
        'rgxp'      => 'digit'
    ],
    'sql'         => 'int(10) unsigned NOT NULL default \'0\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_selection'] = [
    'label'       => 'range_selection.label',
    'description' => 'range_selection.description',
    'exclude'     => true,
    'inputType'   => 'multiColumnWizard',
    'eval'        => [
        'useTranslator' => true,
        'tl_class'      => 'w50',
        'columnFields'  => [
            'range'     => [
                'label'     => 'range_selection.label',
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => [
                    'mandatory' => true,
                    'style'     => 'width:100%',
                    'rgxp'      => 'digit'
                ]
            ],
            'isdefault' => [
                'label'     => 'range_selection_default.label',
                'exclude'   => true,
                'inputType' => 'checkbox',
                'eval'      => [
                    'style' => 'width:130px',
                ]
            ],
        ]
    ],
    'sql'         => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_label'] = [
    'label'       => 'range_label.label',
    'description' => 'range_label.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => [
        'tl_class' => 'clr w50'
    ],
    'sql'         => 'blob NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_placeholder'] = [
    'label'       => 'range_placeholder.label',
    'description' => 'range_placeholder.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => [
        'tl_class' => 'w50'
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_template'] = [
    'label'       => 'range_template.label',
    'description' => 'range_template.description',
    'default'     => 'mm_filteritem_default',
    'exclude'     => true,
    'inputType'   => 'select',
    'eval'        => [
        'tl_class' => 'w50',
        'chosen'   => true
    ],
    'sql'         => 'varchar(64) NOT NULL default \'\''
];

/*
 * Fields for data
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['datamode'] = [
    'label'       => 'datamode.label',
    'description' => 'datamode.description',
    'exclude'     => true,
    'inputType'   => 'select',
    'options'     => ['single', 'multi'],
    'reference'   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options'],
    'eval'        => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'clr w50'
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['lookupservice'] = [
    'label'       => 'lookupservice.label',
    'description' => 'lookupservice.description',
    'exclude'     => true,
    'inputType'   => 'multiColumnWizard',
    'eval'        => [
        'useTranslator' => true,
        'tl_class'      => 'clr',
        'helpwizard'    => true,
        'columnFields'  => [
            'lookupservice' => [
                'label'       => 'lookupservice_service.label',
                'description' => 'lookupservice_service.description',
                'exclude'     => true,
                'inputType'   => 'select',
                'eval'        => [
                    'includeBlankOption' => true,
                    'mandatory'          => true,
                    'chosen'             => true,
                    'style'              => 'width:250px'
                ]
            ],
            'apiToken'      => [
                'label'       => 'lookupservice_api_token.label',
                'description' => 'lookupservice_api_token.description',
                'exclude'     => true,
                'inputType'   => 'text',
                'eval'        => [
                    'tl_class' => 'w50'
                ]
            ]
        ]
    ],
    'explanation' => 'filter_lookupservice',
    'sql'         => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['single_attr_id'] = [
    'label'       => 'single_attr_id.label',
    'description' => 'single_attr_id.description',
    'exclude'     => true,
    'inputType'   => 'select',
    'eval'        => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'clr w50',
        'chosen'             => true
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['first_attr_id'] = [
    'label'       => 'first_attr_id.label',
    'description' => 'first_attr_id.description',
    'exclude'     => true,
    'inputType'   => 'select',
    'eval'        => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'clr w50',
        'chosen'             => true
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['second_attr_id'] = [
    'label'       => 'second_attr_id.label',
    'description' => 'second_attr_id.description',
    'exclude'     => true,
    'inputType'   => 'select',
    'eval'        => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

/*
 * Placeholder
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['placeholder'] = [
    'label'       => 'placeholder.label',
    'description' => 'placeholder.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => [
        'tl_class' => 'w50'
    ],
    'sql'         => 'varchar(255) NOT NULL default \'\''
];

<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2021 The MetaModels team.
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
 * @copyright  2012-2021 The MetaModels team.
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
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['none', 'preset', 'get'],
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options'],
    'eval'      => [
        'tl_class'       => 'w50 w50x',
        'doNotSaveEmpty' => true,
        'alwaysSave'     => true,
        'submitOnChange' => true,
        'mandatory'      => true
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['country_preset'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_preset'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class'  => 'w50 w50x',
        'mandatory' => true
    ],
    'sql'      => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['country_get'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_get'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class'  => 'w50 w50x',
        'mandatory' => true
    ],
    'sql'      => 'text NULL'
];

/*
 * Fields for range
 */

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['rangemode'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['free', 'preset', 'selection'],
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options'],
    'eval'      => [
        'tl_class'           => 'w50 w50x',
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_preset'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_preset'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class'  => 'w50 w50x',
        'mandatory' => true,
        'rgxp'      => 'digit'
    ],
    'sql'      => 'int(10) unsigned NOT NULL default \'0\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_selection'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => [
        'tl_class'     => 'clr',
        'columnFields' => [
            'range' => [
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => [
                    'mandatory' => true,
                    'style'     => 'width:230px',
                    'rgxp'      => 'digit'
                ]
            ],
            'isdefault' => [
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection_default'],
                'exclude'   => true,
                'inputType' => 'checkbox',
                'eval'      => [
                    'style'     => 'width:230px',
                ]
            ],
        ]
    ],
    'sql'      => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_label'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_label'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class' => 'clr w50'
    ],
    'sql'      => 'blob NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_placeholder'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_placeholder'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class' => 'w50'
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['range_template'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_template'],
    'default'   => 'mm_filteritem_default',
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => [
        'tl_class' => 'w50',
        'chosen'   => true
    ],
    'sql'      => 'varchar(64) NOT NULL default \'\''
];

/*
 * Fields for data
 */

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['datamode'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['single', 'multi'],
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options'],
    'eval'      => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['lookupservice'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => [
        'tl_class'     => 'clr',
        'helpwizard'   => true,
        'columnFields' => [
            'lookupservice' => [
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice'],
                'exclude'   => true,
                'inputType' => 'select',
                'eval'      => [
                    'includeBlankOption' => true,
                    'mandatory'          => true,
                    'chosen'             => true,
                    'style'              => 'width:250px'
                ]
            ],
            'apiToken' => [
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice']['api_token'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => [
                    'tl_class' => 'w50'
                ]
            ]
        ]
    ],
    'explanation' => 'filter_lookupservice',
    'sql'         => 'text NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['single_attr_id'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['single_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['first_attr_id'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['first_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['second_attr_id'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['second_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => [
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

/*
 * Placeholder
 */

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['placeholder'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['placeholder'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class' => 'w50'
    ],
    'sql'      => 'varchar(255) NOT NULL default \'\''
];

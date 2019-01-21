<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2019 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/filter_perimetersearch
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_metamodel_perimetersearch
 */
$GLOBALS['TL_DCA']['tl_metamodel_perimetersearch'] = [
    // Config
    'config' => [
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ]
        ]
    ],
    // Fields
    'fields' => [
        'id' => [
            'sql'                       => 'int(10) unsigned NOT NULL auto_increment'
        ],
        'search' => [
            'sql'                       => 'text NULL'
        ],
        'country' => [
            'sql'                       => 'varchar(255) NOT NULL default \'\''
        ],
        'geo_lat' => [
            'sql'                       => 'text NULL'
        ],
        'geo_long' => [
            'sql'                       => 'text NULL'
        ]
    ]
];

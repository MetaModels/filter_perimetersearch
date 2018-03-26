<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2018 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage FilterPerimetersearch
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_metamodel_perimetersearch
 */
$GLOBALS['TL_DCA']['tl_metamodel_perimetersearch'] = array
(
    // Config
    'config' => array
    (
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        )
    ),
    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => 'int(10) unsigned NOT NULL auto_increment'
        ),
        'search' => array
        (
            'sql'                        => 'text NULL'
        ),
        'country' => array
        (
            'sql'                        => 'varchar(255) NOT NULL default \'\''
        ),
        'geo_lat' => array
        (
            'sql'                        => 'text NULL'
        ),
        'geo_long' => array
        (
            'sql'                        => 'text NULL'
        )
    )
);

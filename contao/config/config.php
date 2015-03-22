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
 * Plugins for geo resolving.
 */
$GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class']['google_maps']      =
    'MetaModels\Filter\Helper\Perimetersearch\LookUp\Provider\GoogleMaps';
$GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class']['open_street_maps'] =
    'MetaModels\Filter\Helper\Perimetersearch\LookUp\Provider\OpenStreetMaps';

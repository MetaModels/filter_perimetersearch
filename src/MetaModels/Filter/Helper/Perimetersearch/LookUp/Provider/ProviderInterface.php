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

namespace MetaModels\Filter\Helper\Perimetersearch\LookUp\Provider;

use MetaModels\Filter\Helper\Perimetersearch\LookUp\Container;

/**
 * Class MetaModelsCatchmentAreaGeoLookUpInterface
 *
 * Provide methods for decoding messages from look up services.
 *
 * @package       MetaModels
 * @subpackage    PerimeterSearch
 * @author        Stefan Heimes <stefan_heimes@hotmail.com>
 */
interface ProviderInterface
{
    /**
     * Find coordinates for given adress
     *
     * @param string $street
     *
     * @param string $postal
     *
     * @param string $city        Name of city
     *
     * @param string $country     2-letter country code
     *
     * @param string $fullAddress Address string without specific format
     *
     * @return Container
     */
    public function getCoordinates($street = null, $postal = null, $city = null, $country = null, $fullAddress = null);

}

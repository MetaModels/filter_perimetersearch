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
abstract class ProviderInterface
{

    /**
     * Get a list with all countries.
     *
     * @return array
     */
    public function getCountries()
    {
        return \Controller::getCountries();
    }

    /**
     * Search the full name of a country.
     *
     * @param string $strShort The short tag for the country.
     *
     * @return null|string Null on error or the full name as string.
     */
    public function getFullCountryName($strShort)
    {
        $arrCountries = $this->getCountries();
        if (array_key_exists($strShort, $arrCountries)) {
            return $arrCountries[$strShort];
        }

        return null;
    }

    /**
     * Build the query string.
     *
     * @param string $street
     *
     * @param string $postal
     *
     * @param string $city
     *
     * @param string $country
     *
     * @param string $fullAddress
     *
     * @return string
     */
    public function getQueryString(
        $street = null,
        $postal = null,
        $city = null,
        $country = null,
        $fullAddress = null
    ) {
        // If we have a full address use it.
        if ($fullAddress) {
            // If there is a country try to use the long form.
            if ($country !== null && ($fullCountryName = $this->getFullCountryName($country)) !== null) {
                return sprintf('%s, %s', $fullAddress, $fullCountryName);
            } elseif ($country !== null) {
                // If there is no long form use it as is it.
                return sprintf('%s, %s', $fullAddress, $country);
            } else {
                // Or just the full one.
                return $fullAddress;
            }
        } else {
            // Try to solve the country.
            if ($country !== null && ($fullCountryName = $this->getFullCountryName($country)) !== null) {
                $country = $fullCountryName;
            }

            return sprintf(
                "%s, %s %s, %s"
                , $street
                , $postal
                , $city
                , $country
            );
        }
    }

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
    abstract public function getCoordinates(
        $street = null,
        $postal = null,
        $city = null,
        $country = null,
        $fullAddress = null
    );

}

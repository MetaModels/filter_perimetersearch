<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2017 The MetaModels team.
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
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\FilterHelper;

/**
 * Class MetaModelsCatchmentAreaGeoLookUpInterface.
 *
 * Provide methods for decoding messages from look up services.
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
     * @param string $street      The street.
     *
     * @param string $postal      The postal code.
     *
     * @param string $city        Name of city.
     *
     * @param string $country     A 2-letter country code.
     *
     * @param string $fullAddress Address string without specific format.
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
                '%s, %s %s, %s',
                $street,
                $postal,
                $city,
                $country
            );
        }
    }

    /**
     * Find coordinates for given address.
     *
     * @param string $street      The street.
     *
     * @param string $postal      The postal code.
     *
     * @param string $city        Name of city.
     *
     * @param string $country     A 2-letter country code.
     *
     * @param string $fullAddress Address string without specific format.
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

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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\FilterHelper;

use Contao\Controller;

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
        /** @psalm-suppress DeprecatedMethod */
        return Controller::getCountries();
    }

    /**
     * Search the full name of a country.
     *
     * @param string $shortTag The short tag for the country.
     *
     * @return null|string Null on error or the full name as string.
     */
    public function getFullCountryName($shortTag)
    {
        $countries = $this->getCountries();
        if (\array_key_exists($shortTag, $countries)) {
            return $countries[$shortTag];
        }

        return null;
    }

    /**
     * Build the query string.
     *
     * @param string $street      The street.
     * @param string $postal      The postal code.
     * @param string $city        Name of city.
     * @param string $country     A 2-letter country code.
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
        if (null !== $fullAddress) {
            // If there is a country try to use the long form.
            if ((null !== $country) && (null !== ($fullCountryName = $this->getFullCountryName($country)))) {
                return \sprintf('%s, %s', $fullAddress, $fullCountryName);
            }

            if (null !== $country) {
                // If there is no long form use it as is it.
                return \sprintf('%s, %s', $fullAddress, $country);
            }

            // Or just the full one.
            return $fullAddress;
        }

        // Try to solve the country.
        if ((null !== $country) && (null !== ($fullCountryName = $this->getFullCountryName($country)))) {
            $country = $fullCountryName;
        }

        return \sprintf(
            '%s, %s %s, %s',
            $street ?? '',
            $postal ?? '',
            $city ?? '',
            $country ?? ''
        );
    }

    /**
     * Find coordinates for given address.
     *
     * @param string|null $street      The street.
     * @param string|null $postal      The postal code.
     * @param string|null $city        Name of city.
     * @param string|null $country     A 2-letter country code.
     * @param string|null $fullAddress Address string without specific format or string with two coordinates.
     * @param string|null $apiToken    Optional the API token.
     *
     * @return Container
     */
    abstract public function getCoordinates(
        $street = null,
        $postal = null,
        $city = null,
        $country = null,
        $fullAddress = null,
        $apiToken = null
    );
}

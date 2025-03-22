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

use Contao\Request;

/**
 * Lookup class for Google API.
 */
class GoogleMaps extends ProviderInterface
{
    /**
     * Google API call.
     *
     * @var string
     */
    protected $googleUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=de';

    /**
     * Google API call.
     *
     * @var string
     *
     * @deprecated Deprecated since 2.1 and where removed in 3.0. Use $googleUrl instead.
     */
    protected $strGoogleUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=de';

    /**
     * {@inheritdoc}
     */
    public function getCoordinates(
        $street = null,
        $postal = null,
        $city = null,
        $country = null,
        $fullAddress = null,
        $apiToken = null
    ) {
        // Generate a new container.
        $container = new Container();

        // Set the query string.
        $query = $this->getQueryString($street, $postal, $city, $country, $fullAddress);
        $container->setSearchParam($query);

        $request = new Request();

        $apiUrlParameter = \is_string($apiToken) ? '&key=' . $apiToken : '';
        $request->send(\sprintf($this->googleUrl . '%s', \rawurlencode($query), $apiUrlParameter));
        $container->setUri(\sprintf($this->googleUrl . '%s', \rawurlencode($query), $apiUrlParameter));

        if (200 === $request->code) {
            $response = \json_decode($request->response, true);

            if (!empty($response['status']) && ('OK' === $response['status'])) {
                $container->setLatitude($response['results'][0]['geometry']['location']['lat']);
                $container->setLongitude($response['results'][0]['geometry']['location']['lng']);
            } elseif (!empty($response['error_message'])) {
                $container->setError(true);
                $container->setErrorMsg($response['error_message']);
            } else {
                $container->setError(true);
                $container->setErrorMsg($response['Status']['error_message']);
            }
        } else {
            // Okay nothing work. So set all to Error.
            $container->setError(true);
            $container->setErrorMsg('Could not find coordinates for address "' . $query . '"');
        }

        return $container;
    }
}

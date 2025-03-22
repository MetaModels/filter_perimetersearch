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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\FilterHelper;

use Contao\Request;

/**
 * Lookup class for OpenStreetMap API.
 */
class OpenStreetMaps extends ProviderInterface
{
    /**
     * Open street map API call.
     *
     * @var string
     */
    protected $url = 'https://nominatim.openstreetmap.org/search?q=%s&format=json&limit=1';
    /**
     * Open street map API call.
     *
     * @var string
     *
     * @deprecated Deprecated since 2.1 and where removed in 3.0. Use $url instead.
     */
    protected $strUrl = 'https://nominatim.openstreetmap.org/search?q=%s&format=json&limit=1';

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

        $request->send(\sprintf($this->url, \rawurlencode($query)));
        $response     = \json_decode($request->response);
        $responseItem = $response[0];

        if (200 === $request->code) {
            if (!empty($responseItem->place_id)) {
                $container->setLatitude($responseItem->lat);
                $container->setLongitude($responseItem->lon);
            } else {
                $container->setError(true);
                $container->setErrorMsg('No data from OpenStreetMap for ' . $query);
            }
        } else {
            // Okay nothing work. So set all to Error.
            $container->setError(true);
            $container->setErrorMsg('No response from OpenStreetMap for address "' . $query . '"');
        }

        return $container;
    }
}

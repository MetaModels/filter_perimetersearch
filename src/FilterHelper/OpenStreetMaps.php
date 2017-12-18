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
 * Lookup class for open streetmap.
 */
class OpenStreetMaps extends ProviderInterface
{
    /**
     * Google API call.
     *
     * @var string
     */
    protected $strUrl = 'http://nominatim.openstreetmap.org/search?q=%s&format=json&limit=1';

    /**
     * {@inheritdoc}
     */
    public function getCoordinates($street = null, $postal = null, $city = null, $country = null, $fullAddress = null)
    {
        // Generate a new container.
        $objReturn = new Container();

        // Set the query string.
        $sQuery = $this->getQueryString($street, $postal, $city, $country, $fullAddress);
        $objReturn->setSearchParam($sQuery);

        $oRequest = null;
        $oRequest = new \Request();

        $oRequest->send(sprintf($this->strUrl, rawurlencode($sQuery)));
        $aResponse   = json_decode($oRequest->response);
        $objResponse = $aResponse[0];

        if ($oRequest->code == 200) {
            if (!empty($objResponse->place_id)) {
                $objReturn->setLatitude($objResponse->lat);
                $objReturn->setLongitude($objResponse->lon);
            } else {
                $objReturn->setError(true);
                $objReturn->setErrorMsg('No data from OpenStreetMap for ' . $sQuery);
            }
        } else {
            // Okay nothing work. So set all to Error.
            $objReturn->setError(true);
            $objReturn->setErrorMsg('No response from OpenStreetMap for address "' . $sQuery . '"');
        }

        return $objReturn;
    }
}

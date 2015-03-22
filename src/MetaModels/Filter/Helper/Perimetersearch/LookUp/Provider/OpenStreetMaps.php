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

/**
 * Lookup class for google.
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

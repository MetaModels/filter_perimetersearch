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
class GoogleMaps implements ProviderInterface
{
    /**
     * Google API call
     *
     * @var string
     */
    protected $strGoogleUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=de";

    /**
     * Google alternative API call
     *
     * @var string
     */
    protected $strGoogleAltenativUrl = "http://maps.google.com/maps/geo?q=%s&output=json&oe=utf8&sensor=false&hl=de";

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
    public function getCoordinates($street = null, $postal = null, $city = null, $country = null, $fullAddress = null)
    {
        // Generate a new container.
        $objReturn = new Container();

        // Find coordinates using google maps api.
        $sQuery = sprintf(
            "%s %s %s %s"
            , $street
            , $postal
            , $city
            , $country
        );

        $sQuery = $fullAddress ? $fullAddress : $sQuery;

        // Set the query string.
        $objReturn->setSearchParam($sQuery);

        $oRequest = null;
        $oRequest = new \Request();

        $oRequest->send(sprintf($this->strGoogleUrl, rawurlencode($sQuery)));

        if ($oRequest->code == 200) {
            $aResponse = json_decode($oRequest->response, 1);

            if (!empty($aResponse['status']) && $aResponse['status'] == 'OK') {
                $objReturn->setLatitude($aResponse['results'][0]['geometry']['location']['lat']);
                $objReturn->setLongitude($aResponse['results'][0]['geometry']['location']['lng']);

                return $objReturn;
            } else {
                // Try alternative api if google blocked us.
                $oRequest->send(sprintf($this->strGoogleAltenativUrl, rawurlencode($sQuery)));

                if ($oRequest->code == 200) {
                    $aResponse = json_decode($oRequest->response, 1);

                    if (!empty($aResponse['Status']) && $aResponse['Status']['code'] == 200) {
                        $objReturn->setLatitude($aResponse['Placemark'][0]['Point']['coordinates'][1]);
                        $objReturn->setLongitude($aResponse['Placemark'][0]['Point']['coordinates'][0]);

                        return $objReturn;
                    }
                }
            }
        }

        // Okay nothing work. So set all to Error.
        $objReturn->setError("true");
        $objReturn->setErrorMsg('Could not find coordinates for address "' . $sQuery . '"');

        return $objReturn;
    }

}

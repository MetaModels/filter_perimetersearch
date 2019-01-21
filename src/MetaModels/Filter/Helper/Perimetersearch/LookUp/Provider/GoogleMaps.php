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

namespace MetaModels\Filter\Helper\Perimetersearch\LookUp\Provider;

/**
 * Lookup class for google.
 */
class GoogleMaps extends ProviderInterface
{
    /**
     * Google API call.
     *
     * @var string
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
        $objReturn = new Container();

        // Set the query string.
        $sQuery = $this->getQueryString($street, $postal, $city, $country, $fullAddress, $apiToken);
        $objReturn->setSearchParam($sQuery);

        $oRequest = new \Request();

        $apiUrlParameter = $apiToken ? '&key=' . $apiToken : '';
        $oRequest->send(\sprintf($this->strGoogleUrl . '%s', \rawurlencode($sQuery), $apiUrlParameter));
        $objReturn->setUri(\sprintf($this->strGoogleUrl . '%s', \rawurlencode($sQuery), $apiUrlParameter));

        if ($oRequest->code == 200) {
            $aResponse = \json_decode($oRequest->response, 1);

            if (!empty($aResponse['status']) && $aResponse['status'] == 'OK') {
                $objReturn->setLatitude($aResponse['results'][0]['geometry']['location']['lat']);
                $objReturn->setLongitude($aResponse['results'][0]['geometry']['location']['lng']);
            } elseif (!empty($aResponse['error_message'])) {
                $objReturn->setError(true);
                $objReturn->setErrorMsg($aResponse['error_message']);
            } else {
                $objReturn->setError(true);
                $objReturn->setErrorMsg($aResponse['Status']['error_message']);
            }
        } else {
            // Okay nothing work. So set all to Error.
            $objReturn->setError(true);
            $objReturn->setErrorMsg('Could not find coordinates for address "' . $sQuery . '"');
        }

        return $objReturn;
    }
}

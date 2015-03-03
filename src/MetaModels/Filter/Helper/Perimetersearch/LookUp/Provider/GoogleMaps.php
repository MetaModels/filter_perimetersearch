<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package	   MetaModels
 * @subpackage PerimeterSearch
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

use PerimetersearchLookUpInterface;
use PerimetersearchLookUpContainer;

/**
 * Lookup class for google.
 */
class PerimetersearchLookUpGoogleMaps implements PerimetersearchLookUpInterface
{
	/**
	 * Google API call
	 * @var String 
	 */
	protected $strGoogleUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=de";
	
	/**
	 * Google alternative API call
	 * @var String 
	 */
	protected $strGoogleAltenativUrl = "http://maps.google.com/maps/geo?q=%s&output=json&oe=utf8&sensor=false&hl=de";

	/**
	 * Find coordinates for given adress
	 * 
	 * @param string Street
	 * @param string Postal/ZIP Code
	 * @param string Name of city
	 * @param string 2-letter country code
	 * @param string Adress string without specific format
	 * 
	 * @return array
	 */
	public function getCoordinates($street = NULL, $postal = NULL, $city = NULL, $country = NULL, $fullAdress = NULL)
	{
		// Generate a new container.
		$objReturn = new PerimetersearchLookUpContainer();
		
		// Find coordinates using google maps api.
		$sQuery = sprintf(
				"%s %s %s %s"
				, $street
				, $postal
				, $city
				, $country
		);

		$sQuery = $fullAdress ? $fullAdress : $sQuery;

		// Set the query string.
		$objReturn->setSearchParam($sQuery);

		$oRequest = NULL;
		$oRequest = new Request();

		$oRequest->send(sprintf($this->strGoogleUrl, rawurlencode($sQuery)));

		$hasError = false;

		if ($oRequest->code == 200)
		{
			$aResponse	 = array();
			$aResponse	 = json_decode($oRequest->response, 1);

			if (!empty($aResponse['status']) && $aResponse['status'] == 'OK')
			{
				$objReturn->setLatitude($aResponse['results'][0]['geometry']['location']['lat']);
				$objReturn->setLongitude($aResponse['results'][0]['geometry']['location']['lng']);

				return $objReturn;
			}
			else
			{
				// Try alternative api if google blocked us.
				$oRequest->send(sprintf($this->strGoogleAltenativUrl, rawurlencode($sQuery)));

				if ($oRequest->code == 200)
				{
					$aResponse	 = array();
					$aResponse	 = json_decode($oRequest->response, 1);

					if (!empty($aResponse['Status']) && $aResponse['Status']['code'] == 200)
					{
						$objReturn->setLatitude($aResponse['Placemark'][0]['Point']['coordinates'][1]);
						$objReturn->setLongitude($aResponse['Placemark'][0]['Point']['coordinates'][0]);

						return $objReturn;
					}
				}
			}
		}

		// Okay nothing work. So set all to Error.
		$objReturn->setError("true");
		$objReturn->setErrorMsg('Could not find coordinates for adress "' . $sQuery . '"');

		// Return nothing.
		return false;
	}

}
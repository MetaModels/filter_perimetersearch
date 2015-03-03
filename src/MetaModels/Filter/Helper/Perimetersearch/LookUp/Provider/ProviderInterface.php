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

/**
 * Class MetaModelsCatchmentAreaGeoLookUpInterface
 *
 * Provide methods for decoding messages from look up services.
 * @package	   MetaModels
 * @subpackage PerimeterSearch
 * @author	   Stefan Heimes <stefan_heimes@hotmail.com>
 */
interface PerimetersearchLookUpInterface
{
    /**
	 * Find coordinates for given adress
	 * 
	 * @param string Street
	 * @param string Postal/ZIP Code
	 * @param string Name of city
	 * @param string 2-letter country code
	 * @param string Adress string without specific format
	 * @return array
	 */
	public function getCoordinates( $street=NULL, $postal=NULL, $city=NULL, $country=NULL, $fullAdress=NULL );

}



?>

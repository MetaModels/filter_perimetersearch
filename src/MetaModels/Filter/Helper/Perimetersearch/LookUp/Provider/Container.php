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
 * Class MetaModelsCatchmentAreaGeoContainer
 *
 * Provide methods for decoding messages from look up services.
 *
 * @package       MetaModels
 * @subpackage    PerimeterSearch
 * @author        Stefan Heimes <stefan_heimes@hotmail.com>
 */
class Container
{
    /**
     * Lat
     *
     * @var mixed
     */
    protected $mixLatitude = 0;

    /**
     * long
     *
     * @var mixed
     */
    protected $mixLongitude = 0;

    /**
     * Search param
     *
     * @var string
     */
    protected $strSearchParam = '';

    /**
     * Distance for the search.
     *
     * @var int
     */
    protected $intDistance = 0;

    /**
     * Show if we have an error
     *
     * @var boolean
     */
    protected $blnError = false;

    /**
     * Error message
     *
     * @var string
     */
    protected $strErrorMsg = "";

    public function getLatitude()
    {
        return $this->mixLatitude;
    }

    public function setLatitude($mixLat)
    {
        $this->mixLatitude = $mixLat;
    }

    public function getLongitude()
    {
        return $this->mixLongitude;
    }

    public function setLongitude($mixLong)
    {
        $this->mixLongitude = $mixLong;
    }

    public function getSearchParam()
    {
        return $this->strSearchParam;
    }

    public function setSearchParam($strSearchParam)
    {
        $this->strSearchParam = $strSearchParam;
    }

    public function hasError()
    {
        return $this->blnError;
    }

    public function setError($blnHasError)
    {
        $this->blnError = $blnHasError;
    }

    public function getErrorMsg()
    {
        return $this->strErrorMsg;
    }

    public function setErrorMsg($strErrorMsg)
    {
        $this->strErrorMsg = $strErrorMsg;
    }

    public function getDistance()
    {
        return $this->intDistance;
    }

    public function setDistance($intDistance)
    {
        $this->intDistance = $intDistance;
    }
}

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
 * Class MetaModelsCatchmentAreaGeoContainer.
 *
 * Provide methods for decoding messages from look up services.
 */
class Container
{
    /**
     * Lat.
     *
     * @var mixed
     */
    protected $latitude = 0;

    /**
     * Long.
     *
     * @var mixed
     */
    protected $longitude = 0;

    /**
     * Search param.
     *
     * @var string
     */
    protected $searchParam = '';

    /**
     * Distance for the search.
     *
     * @var int
     */
    protected $distance = 0;

    /**
     * Show if we have an error.
     *
     * @var boolean
     */
    protected $errorFlag = false;

    /**
     * The request uri.
     *
     * @var string
     */
    protected $uri = '';

    /**
     * Error message.
     *
     * @var string
     */
    protected $errorMsg = '';

    /**
     * Return the latitude.
     *
     * @return float|int
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the latitude.
     *
     * @param float|int $lat The latitude value.
     *
     * @return $this
     */
    public function setLatitude($lat)
    {
        $this->latitude = $lat;

        return $this;
    }

    /**
     * Return the longitude.
     *
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the latitude.
     *
     * @param float|int $long The longitude value.
     *
     * @return $this
     */
    public function setLongitude($long)
    {
        $this->longitude = $long;

        return $this;
    }

    /**
     * Return parameters from the search.
     *
     * @return mixed
     */
    public function getSearchParam()
    {
        return $this->searchParam;
    }

    /**
     * Set the latitude.
     *
     * @param mixed $searchParam The search parameters.
     *
     * @return $this
     */
    public function setSearchParam($searchParam)
    {
        $this->searchParam = $searchParam;

        return $this;
    }

    /**
     * Check if there was an error.
     *
     * @return bool True => Error | False => No Error.
     */
    public function hasError()
    {
        return $this->errorFlag;
    }

    /**
     * Set the error flag.
     *
     * @param bool $hasError True => Error | False => No Error.
     *
     * @return $this
     */
    public function setError($hasError)
    {
        $this->errorFlag = $hasError;

        return $this;
    }

    /**
     * Return the error messages.
     *
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Set the error message.
     *
     * @param string $strErrorMsg The error message.
     *
     * @return $this
     */
    public function setErrorMsg($strErrorMsg)
    {
        $this->errorMsg = $strErrorMsg;

        return $this;
    }

    /**
     * Get the distance value.
     *
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set the distance value.
     *
     * @param int $distance The distance value.
     *
     * @return $this
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get the URI for the request.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the uri string.
     *
     * @param string $uri The uri string.
     *
     * @return string
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }
}

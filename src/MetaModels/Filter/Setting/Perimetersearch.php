<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2018 The MetaModels team.
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
 * @author     Christopher Bölter <christopher@boelter.eu>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\Filter\Setting;

use MetaModels\Attribute\IAttribute;
use MetaModels\Filter\Helper\Perimetersearch\LookUp\Provider\Container;
use MetaModels\Filter\IFilter;
use MetaModels\Filter\Rules\StaticIdList;
use MetaModels\FrontendIntegration\FrontendFilterOptions;

/**
 * Filter "select field" for FE-filtering, based on filters by the meta models team.
 */
class Perimetersearch extends SimpleLookup
{
    /**
     * Overrides the parent implementation to always return true, as this setting is always optional.
     *
     * @return bool true if all matches shall be returned, false otherwise.
     */
    public function allowEmpty()
    {
        return true;
    }

    /**
     * Overrides the parent implementation to always return true, as this setting is always available for FE filtering.
     *
     * @return bool true as this setting is always available.
     */
    public function enableFEFilterWidget()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getParamName()
    {
        if ($this->get('urlparam')) {
            return $this->get('urlparam');
        }

        if ($this->get('datamode') == 'single') {
            $objAttribute = $this
                ->getMetaModel()
                ->getAttribute($this->get('single_attr_id'));
        } elseif ($this->get('datamode') == 'multi') {
            $objAttribute = $this
                ->getMetaModel()
                ->getAttribute($this->get('first_attr_id'));
        } else {
            return '';
        }

        // Return name if we have a result.
        return $objAttribute->getColName();
    }

    /**
     * Get the param name for the range.
     *
     * @return string
     */
    protected function getParamNameRange()
    {
        return $this->getParamName() . '_range';
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRules(IFilter $objFilter, $arrFilterUrl)
    {
        $objMetaModel      = $this->getMetaModel();
        $strParamName      = $this->getParamName();
        $strParamNameRange = $this->getParamNameRange();
        $strParamValue     = $arrFilterUrl[$strParamName];
        $intDist           = intval($arrFilterUrl[$strParamNameRange]);

        // Check if we have a value.
        if (empty($strParamValue)) {
            return;
        }

        // If range mode is preset use this value.
        if ($this->get('rangemode') == 'preset') {
            $intDist = intval($this->get('range_preset'));
        }

        // Try to get a country.
        $strCountry = $this->getCountyInformation();

        // Search for the geolocation.
        $objContainer = $this->lookupGeo($strParamValue, $strCountry);

        // Okay we cant find a entry. So search for nothing.
        if ($objContainer == null || $objContainer->hasError()) {
            return;
        }

        // Set the distance for the search.
        $objContainer->setDistance($intDist);

        if ($this->get('datamode') == 'single') {
            // Get the attribute.
            $objAttribute = $objMetaModel->getAttribute($this->get('single_attr_id'));

            // Search for the geolocation attribute.
            if ($objAttribute->get('type') == 'geolocation') {
                $this->doSearchForAttGeolocation($objContainer, $objFilter);
            }
        } elseif ($this->get('datamode') == 'multi') {
            // Get the attributes.
            $objFirstAttribute  = $objMetaModel->getAttribute($this->get('first_attr_id'));
            $objSecondAttribute = $objMetaModel->getAttribute($this->get('second_attr_id'));

            // Search for two simple attributes.
            $this->doSearchForTwoSimpleAtt($objContainer, $objFilter, $objFirstAttribute, $objSecondAttribute);
        }
    }

    /**
     * Try to get a valid country information.
     *
     * @return string|null The country short tag (2-letters) or null.
     */
    protected function getCountyInformation()
    {
        // Get the country for the lookup.
        $strCountry = null;

        if ($this->get('countrymode') === 'get' && $this->get('country_get')) {
            $getValue = \Input::get($this->get('country_get'));
            $getValue = trim($getValue);
            if (!empty($getValue)) {
                $strCountry = $getValue;
            }
        } elseif ($this->get('countrymode') === 'get' && $this->get('country_get')) {
            $getValue = \Input::post($this->get('country_get'));
            $getValue = trim($getValue);
            if (!empty($getValue)) {
                $strCountry = $getValue;
            }
        } elseif ($this->get('countrymode') === 'preset') {
            $strCountry = $this->get('country_preset');
        }

        return $strCountry;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterFilterNames()
    {
        if (($strParamName = $this->getParamName())) {
            return array(
                $strParamName              => ($this->get('label') ? $this->get('label') : $this->getAttributeName()),
                $this->getParamNameRange() => ($this->get('label') ?
                        $this->get('label') :
                        $this->getAttributeName()) . ' - Range'
            );
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterDCA()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getParameterFilterWidgets(
        $arrIds,
        $arrFilterUrl,
        $arrJumpTo,
        FrontendFilterOptions $objFrontendFilterOptions
    ) {
        // If defined as static, return nothing as not to be manipulated via editors.
        if (!$this->enableFEFilterWidget()) {
            return array();
        }

        $arrReturn                     = array();
        $GLOBALS['MM_FILTER_PARAMS'][] = $this->getParamName();
        $GLOBALS['MM_FILTER_PARAMS'][] = $this->getParamNameRange();

        // Address search.
        $arrWidget      = $this->getSearchWidget($objFrontendFilterOptions);
        $arrRangeWidget = $this->getRangeWidget();

        // Add filter.
        $arrReturn[$this->getParamName()] = $this
            ->prepareFrontendFilterWidget($arrWidget, $arrFilterUrl, $arrJumpTo, $objFrontendFilterOptions);

        // Add range filter if we have one.
        if ($arrRangeWidget) {
            $arrReturn[$this->getParamNameRange()] = $this
                ->prepareFrontendFilterWidget($arrRangeWidget, $arrFilterUrl, $arrJumpTo, $objFrontendFilterOptions);
        }

        return $arrReturn;
    }


    /**
     * Get the widget information for the search field.
     *
     * @param FrontendFilterOptions $objFrontendFilterOptions The FE options.
     *
     * @return array
     */
    private function getSearchWidget(FrontendFilterOptions $objFrontendFilterOptions)
    {
        $arrCount  = array();
        $arrWidget = array(
            'label'     => array(
                ($this->get('label') ? $this->get('label') : $this->getAttributeName()),
                'GET: ' . $this->getParamName()
            ),
            'inputType' => 'text',
            'count'     => $arrCount,
            'showCount' => $objFrontendFilterOptions->isShowCountValues(),
            'eval'      => array(
                'colname'     => $this->getColname(),
                'urlparam'    => $this->getParamName(),
                'template'    => $this->get('template'),
                'placeholder' => $this->get('placeholder'),
            )
        );

        return $arrWidget;
    }


    /**
     * Get the widget for the distance.
     *
     * @return array|null
     */
    private function getRangeWidget()
    {
        if ($this->get('rangemode') == 'selection') {
            // Get all range options.
            $arrRangeOptions = array();

            foreach (deserialize($this->get('range_selection'), true) as $arrRange) {
                $arrRangeOptions[$arrRange['range']] = $arrRange['range'] . 'km';
            }

            $arrRangeWidget = array(
                'label'     => array(
                    ($this->get('range_label') ? $this->get('range_label') : $this->getAttributeName() . ' Range '),
                    'GET: ' . $this->getParamNameRange()
                ),
                'inputType' => 'select',
                'options'   => $arrRangeOptions,
                'eval'      => array(
                    'includeBlankOption' => true,
                    'colname'            => $this->getColname(),
                    'urlparam'           => $this->getParamNameRange(),
                    'template'           => $this->get('range_template'),
                )
            );

            return $arrRangeWidget;
        } elseif ($this->get('rangemode') == 'free') {
            $arrRangeWidget = array(
                'label'     => array(
                    ($this->get('range_label') ? $this->get('range_label') : $this->getAttributeName() . ' Range '),
                    'GET: ' . $this->getParamNameRange()
                ),
                'inputType' => 'text',
                'eval'      => array(
                    'colname'     => $this->getColname(),
                    'urlparam'    => $this->getParamNameRange(),
                    'template'    => $this->get('range_template'),
                    'placeholder' => $this->get('range_placeholder'),
                )
            );

            return $arrRangeWidget;
        }
        {
            return null;
        }
    }

    /**
     * Get the attribute name/s.
     *
     * @return string|null
     */
    protected function getColname()
    {
        if (($strParamName = $this->getParamName()) && $this->get('datamode') == 'single') {
            return $this->getMetaModel()->getAttribute($this->get('single_attr_id'))->getColname();
        } elseif (($strParamName = $this->getParamName()) && $this->get('datamode') == 'multi') {
            return $this->getMetaModel()->getAttribute($this->get('first_attr_id'))->getColname();
        }

        return null;
    }

    /**
     * Get the attribute name/s.
     *
     * @return string|null
     */
    protected function getAttributeName()
    {
        if (($strParamName = $this->getParamName()) && $this->get('datamode') == 'single') {
            return $this->getMetaModel()->getAttribute($this->get('single_attr_id'))->getName();
        } elseif (($strParamName = $this->getParamName()) && $this->get('datamode') == 'multi') {
            $objFirstAttribute  = $this->getMetaModel()->getAttribute($this->get('first_attr_id'));
            $objSecondAttribute = $this->getMetaModel()->getAttribute($this->get('second_attr_id'));

            $strLatName = $objFirstAttribute->getName();
            $strLngName = $objSecondAttribute->getName();

            return $strLatName . '/' . $strLngName;
        }

        return null;
    }

    /**
     * Run the search for the complex attribute geolocation.
     *
     * @param Container $container The container with all information.
     *
     * @param IFilter   $filter    The filter container.
     *
     * @return void
     */
    protected function doSearchForAttGeolocation($container, $filter)
    {
        // Get location.y
        $lat      = $container->getLatitude();
        $lng      = $container->getLongitude();
        $intDist  = $container->getDistance();
        $distance = sprintf(
            'round(sqrt(' .
            'power(2 * pi() / 360 * (%1$s - latitude) * 6371,2)' .
            '+ power(2 * pi() / 360 * (%2$s - longitude) * 6371 * COS( 2 * pi() / 360 * (%1$s + latitude) * 0.5), 2)' .
            '))',
            $lat,
            $lng
        );

        $objResult = \Database::getInstance()
            ->prepare(sprintf(
                'SELECT item_id FROM tl_metamodel_geolocation WHERE %1$s<=? AND att_id=? ORDER BY %1$s',
                $distance
            ))
            ->execute($intDist, $this->getMetaModel()->getAttribute($this->get('single_attr_id'))->get('id'));

        if ($objResult->numRows == 0) {
            $filter->addFilterRule(new StaticIdList(array()));
        } else {
            $filter->addFilterRule(new StaticIdList($objResult->fetchEach('item_id')));
        }
    }

    /**
     * Run the search for the complex attribute geolocation.
     *
     * @param Container  $container     The container with all information.
     *
     * @param IFilter    $filter        The filter container.
     *
     * @param IAttribute $latAttribute  The attribute to filter on.
     *
     * @param IAttribute $longAttribute The attribute to filter on.
     *
     * @return void
     */
    protected function doSearchForTwoSimpleAtt($container, $filter, $latAttribute, $longAttribute)
    {
        // Get location.
        $lat      = $container->getLatitude();
        $lng      = $container->getLongitude();
        $intDist  = $container->getDistance();
        $distance = sprintf(
            'round(sqrt(' .
            'power(2 * pi() / 360 * (%1$s - %3$s) * 6371,2)' .
            '+ power(2 * pi() / 360 * (%2$s - %4$s) * 6371 * COS( 2 * pi() / 360 * (%1$s + %3$s) * 0.5), 2)' .
            '))',
            $lat,
            $lng,
            $latAttribute->getColName(),
            $longAttribute->getColName()
        );

        $objResult = \Database::getInstance()
            ->prepare(
                sprintf(
                    'SELECT id FROM %1$s WHERE %2$s<=? ORDER BY %2$s',
                    $this->getMetaModel()->getTableName(),
                    $distance
                )
            )
            ->execute($intDist);

        if ($objResult->numRows == 0) {
            $filter->addFilterRule(new StaticIdList(array()));
        } else {
            $filter->addFilterRule(new StaticIdList($objResult->fetchEach('id')));
        }
    }

    /**
     * User the provider classes to make a look up.
     *
     * @param string $strAddress The full address to search for.
     *
     * @param string $strCountry The country as 2-letters form.
     *
     * @return Container|null Return the container with all information or null on error.
     */
    protected function lookupGeo($strAddress, $strCountry)
    {
        // Trim the data. Better!
        $strAddress = trim($strAddress);
        $strCountry = trim($strCountry);

        // First check cache.
        $objCacheResult = $this->getFromCache($strAddress, $strCountry);
        if ($objCacheResult !== null) {
            return $objCacheResult;
        }

        // If there is no data from the cache ask google.
        $arrLookupServices = deserialize($this->get('lookupservice'), true);
        if (!count($arrLookupServices)) {
            return false;
        }

        foreach ($arrLookupServices as $arrSettings) {
            try {
                $objCallbackClass = $this->getObjectFromName($arrSettings['lookupservice']);

                // Call the main function.
                if ($objCallbackClass != null) {
                    /** @var Container $objResult */
                    $objResult = $objCallbackClass->getCoordinates(null, null, null, $strCountry, $strAddress);

                    // Check if we have a result.
                    if (!$objResult->hasError()) {
                        $this->addToCache($strAddress, $strCountry, $objResult);
                        return $objResult;
                    }
                }
            } catch (\RuntimeException $exc) {
                // Okay, we have an error try next one.
                continue;
            }
        }

        // When we reach this point, we have no result, so return false.
        return null;
    }

    /**
     * Try to get a object from the given class.
     *
     * @param string $lookupClassName The name of the class.
     *
     * @return null|object
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function getObjectFromName($lookupClassName)
    {
        // Check if we know this class.
        if (!isset($GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'][$lookupClassName])) {
            return null;
        }

        $sClass = $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'][$lookupClassName];

        $objCallbackClass = null;
        $oClass           = new \ReflectionClass($sClass);

        // Fetch singleton instance.
        if ($oClass->hasMethod('getInstance')) {
            $getInstanceMethod = $oClass->getMethod('getInstance');

            // Create a new instance.
            if ($getInstanceMethod->isStatic()) {
                $objCallbackClass = $getInstanceMethod->invoke(null);
                return $objCallbackClass;
            } else {
                $objCallbackClass = $oClass->newInstance();
                return $objCallbackClass;
            }
        } else {
            // Create a normal object.
            $objCallbackClass = $oClass->newInstance();
            return $objCallbackClass;
        }
    }

    /**
     * Retrieve the database.
     *
     * @return \Contao\Database
     */
    private function getDataBase()
    {
        return $this
            ->getMetaModel()
            ->getServiceContainer()
            ->getDatabase();
    }

    /**
     * Add data to the cache.
     *
     * @param string    $address The address which where use for the search.
     *
     * @param string    $country The country.
     *
     * @param Container $result  The container with all information.
     *
     * @return void
     */
    protected function addToCache($address, $country, $result)
    {
        $this->getDataBase()
            ->prepare('INSERT INTO tl_metamodel_perimetersearch %s')
            ->set(array(
                'search'   => $address,
                'country'  => $country,
                'geo_lat'  => $result->getLatitude(),
                'geo_long' => $result->getLongitude(),
            ))
            ->execute();
    }

    /**
     * Get data from cache.
     *
     * @param string $address The address which where use for the search.
     *
     * @param string $country The country.
     *
     * @return Container|null
     */
    protected function getFromCache($address, $country)
    {
        // Check cache.
        $result = $this
            ->getDataBase()
            ->prepare('SELECT * FROM tl_metamodel_perimetersearch WHERE search = ? AND country = ?')
            ->execute($address, $country);

        // If we have no data just return null.
        if ($result->count() === 0) {
            return null;
        }

        // Build a new container.
        $container = new Container();
        $container->setLatitude($result->geo_lat);
        $container->setLongitude($result->geo_long);
        $container->setSearchParam($result->query);

        return $container;
    }
}

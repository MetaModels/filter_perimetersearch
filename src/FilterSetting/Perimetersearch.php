<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2024 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/filter_perimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Christopher Bölter <christopher@boelter.eu>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\FilterSetting;

use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use MetaModels\Attribute\IAttribute;
use MetaModels\Filter\FilterUrlBuilder;
use MetaModels\Filter\IFilter;
use MetaModels\Filter\Rules\StaticIdList;
use MetaModels\Filter\Setting\ICollection;
use MetaModels\Filter\Setting\SimpleLookup;
use MetaModels\FilterPerimetersearchBundle\FilterHelper\Container;
use MetaModels\FilterPerimetersearchBundle\Helper\HaversineSphericalDistance;
use MetaModels\FrontendIntegration\FrontendFilterOptions;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Filter "select field" for FE-filtering, based on filters by the MetaModels team.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Perimetersearch extends SimpleLookup
{
    /**
     * Database connection.
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * Constructor - initialize the object and store the parameters.
     *
     * @param ICollection                   $collection       The parenting filter settings object.
     * @param array                         $data             The attributes for this filter setting.
     * @param EventDispatcherInterface|null $eventDispatcher  The event dispatcher.
     * @param Connection|null               $connection       The database connection.
     * @param FilterUrlBuilder|null         $filterUrlBuilder The filter URL builder.
     */
    public function __construct(
        ICollection $collection,
        array $data,
        EventDispatcherInterface $eventDispatcher = null,
        Connection $connection = null,
        FilterUrlBuilder $filterUrlBuilder = null
    ) {
        parent::__construct($collection, $data, $eventDispatcher, $filterUrlBuilder);

        if (null === $connection) {
            // @codingStandardsIgnoreStart
            @\trigger_error(
                'Connection is missing. It has to be passed in the constructor. Fallback will be dropped.',
                E_USER_DEPRECATED
            );
            // @codingStandardsIgnoreEnd
            $connection = System::getContainer()->get('database_connection');
            assert($connection instanceof Connection);
        }

        $this->connection = $connection;
    }

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
     * Retrieve the filter parameter name to react on.
     *
     * @return string|null
     */
    protected function getParamName()
    {
        if ($this->get('urlparam')) {
            return $this->get('urlparam');
        }

        $attribute = null;
        if ('single' === $this->get('datamode')) {
            $attribute = $this
                ->getMetaModel()
                ->getAttribute($this->get('single_attr_id'));
        } elseif ('multi' === $this->get('datamode')) {
            $attribute = $this
                ->getMetaModel()
                ->getAttribute($this->get('first_attr_id'));
        }

        if (null === $attribute) {
            return 'filter_attr_' . $this->get('id');
        }

        // Return name if we have a result.
        return $attribute->getColName();
    }

    /**
     * Get the param name for the range.
     *
     * @return string
     */
    protected function getParamNameRange()
    {
        return ($this->getParamName() ?? '') . '_range';
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRules(IFilter $objFilter, $arrFilterUrl)
    {
        $metaModel      = $this->getMetaModel();
        $paramName      = $this->getParamName();
        $paramNameRange = $this->getParamNameRange();
        $paramValue     = $arrFilterUrl[$paramName] ?? '';
        $distance       = (int) ($arrFilterUrl[$paramNameRange] ?? 0);

        // Check if we have a value.
        if (empty($paramValue)) {
            return;
        }

        // If range mode is preset use this value.
        if ('preset' === $this->get('rangemode')) {
            $distance = (int) $this->get('range_preset');
        }

        // Try to get a country.
        $country = $this->getCountryInformation();

        // Search for the geolocation.
        $container = $this->lookupGeo($paramValue, $country);

        // Okay we cant find a entry. So search for nothing.
        if ((null === $container) || $container->hasError()) {
            return;
        }

        // Set the distance for the search.
        $container->setDistance($distance);

        if ('single' === $this->get('datamode')) {
            // Get the attribute.
            $attribute = $metaModel->getAttribute($this->get('single_attr_id'));

            // Search for the geolocation attribute.
            if ('geolocation' === $attribute->get('type')) {
                $this->doSearchForAttGeolocation($container, $objFilter);
            }
        } elseif ('multi' === $this->get('datamode')) {
            // Get the attributes.
            $firstAttribute  = $metaModel->getAttribute($this->get('first_attr_id'));
            $secondAttribute = $metaModel->getAttribute($this->get('second_attr_id'));

            // Search for two simple attributes.
            $this->doSearchForTwoSimpleAtt($container, $objFilter, $firstAttribute, $secondAttribute);
        }
    }

    /**
     * Try to get a valid country information.
     *
     * @return string|null The country short tag (2-letters) or null.
     */
    protected function getCountryInformation()
    {
        // Get the country for the lookup.
        $country = null;

        if (('get' === $this->get('countrymode')) && $this->get('country_get')) {
            $getValue = Input::get($this->get('country_get')) ?: Input::post($this->get('country_get'));
            $getValue = \trim($getValue);
            if (!empty($getValue)) {
                $country = $getValue;
            }
        } elseif ('preset' === $this->get('countrymode')) {
            $country = $this->get('country_preset');
        }

        return $country;
    }

    /**
     * {@inheritDoc}
     */
    public function getParameters()
    {
        return \in_array($this->get('rangemode'), ['selection', 'free']) ?
            [$this->getParamName(), $this->getParamNameRange()]
            : [$this->getParamName()];
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterFilterNames()
    {
        if (($paramName = $this->getParamName())) {
            return [
                $paramName                 => ($this->get('label') ?: $this->getAttributeName()) . ' [Umkreissuche]',
                $this->getParamNameRange() => ($this->get('label') ?: $this->getAttributeName()) . ' - Range'
            ];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterDCA()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getParameterFilterWidgets(
        $arrIds,
        $arrArrFilterUrl,
        $arrJumpTo,
        FrontendFilterOptions $objFrontendFilterOptions
    ) {
        // If defined as static, return nothing as not to be manipulated via editors.
        if (!$this->enableFEFilterWidget()) {
            return [];
        }

        $filterWidgets               = [];
        $GLOBALS['MM_FILTER_PARAMS'] = \array_merge(
            ($GLOBALS['MM_FILTER_PARAMS'] ?? []),
            [
                $this->getParamName(),
                $this->getParamNameRange()
            ]
        );

        // Address search.
        $widgets      = $this->getSearchWidget($objFrontendFilterOptions);
        $rangeWidgets = $this->getRangeWidget();

        // Add filter.
        $filterWidgets[$this->getParamName()] = $this
            ->prepareFrontendFilterWidget($widgets, $arrArrFilterUrl, $arrJumpTo, $objFrontendFilterOptions);

        // Add range filter if we have one.
        if ($rangeWidgets) {
            $filterWidgets[$this->getParamNameRange()] = $this
                ->prepareFrontendFilterWidget($rangeWidgets, $arrArrFilterUrl, $arrJumpTo, $objFrontendFilterOptions);
        }

        return $filterWidgets;
    }


    /**
     * Get the widget information for the search field.
     *
     * @param FrontendFilterOptions $frontendFilterOptions The FE options.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    private function getSearchWidget(FrontendFilterOptions $frontendFilterOptions)
    {
        $widget = [
            'label'     => [
                ($this->get('label') ?: $this->getAttributeName()),
                'GET: ' . ($this->getParamName() ?? ''),
            ],
            'inputType' => 'text',
            'count'     => [],
            'showCount' => $frontendFilterOptions->isShowCountValues(),
            'eval'      => [
                'colname'     => $this->getColname(),
                'urlparam'    => $this->getParamName(),
                'template'    => $this->get('template'),
                'placeholder' => $this->get('placeholder')
            ]
        ];

        return $widget;
    }


    /**
     * Get the widget for the distance.
     *
     * @return array|null
     */
    private function getRangeWidget()
    {
        if ('selection' === $this->get('rangemode')) {
            // Get all range options.
            $rangeOptions = [];

            foreach (StringUtil::deserialize($this->get('range_selection'), true) as $rangeItem) {
                $rangeOptions[$rangeItem['range']] = $rangeItem['range'] . ' km';
            }

            $rangeWidget = [
                'label'     => [
                    ($this->get('range_label') ?: ($this->getAttributeName() ?? '') . ' Range '),
                    'GET: ' . $this->getParamNameRange()
                ],
                'inputType' => 'select',
                'options'   => $rangeOptions,
                'eval'      => [
                    'colname'            => $this->getColname(),
                    'urlparam'           => $this->getParamNameRange(),
                    'template'           => $this->get('range_template'),
                    'default'            => $this->get('defaultid'),
                ],
            ];

            return $rangeWidget;
        }

        if ('free' === $this->get('rangemode')) {
            $rangeWidget = [
                'label'     => [
                    ($this->get('range_label') ?: ($this->getAttributeName() ?? '') . ' Range '),
                    'GET: ' . $this->getParamNameRange()
                ],
                'inputType' => 'text',
                'eval'      => [
                    'colname'     => $this->getColname(),
                    'urlparam'    => $this->getParamNameRange(),
                    'template'    => $this->get('range_template'),
                    'placeholder' => $this->get('range_placeholder')
                ]
            ];

            return $rangeWidget;
        }

        return null;
    }

    /**
     * Get the attribute name/s.
     *
     * @return string|null
     */
    protected function getColname()
    {
        if ($this->getParamName() && ('single' === $this->get('datamode'))) {
            return $this->getMetaModel()->getAttribute($this->get('single_attr_id'))->getColname();
        }

        if ($this->getParamName() && ('multi' === $this->get('datamode'))) {
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
        if ($this->getParamName() && ('single' === $this->get('datamode'))) {
            return $this->getMetaModel()->getAttribute($this->get('single_attr_id'))->getName();
        }

        if ($this->getParamName() && ('multi' === $this->get('datamode'))) {
            $firstAttribute  = $this->getMetaModel()->getAttribute($this->get('first_attr_id'));
            $secondAttribute = $this->getMetaModel()->getAttribute($this->get('second_attr_id'));

            $latName = $firstAttribute->getName();
            $lngName = $secondAttribute->getName();

            return $latName . '/' . $lngName;
        }

        return null;
    }

    /**
     * Run the search for the complex attribute geolocation.
     *
     * @param Container $container The container with all information.
     * @param IFilter   $filter    The filter container.
     *
     * @return void
     *
     * @see https://www.movable-type.co.uk/scripts/latlong.html
     */
    protected function doSearchForAttGeolocation($container, $filter)
    {
        // Calculate distance, bearing and more between Latitude/Longitude points
        $distanceCalculation = HaversineSphericalDistance::getFormulaAsQueryPart(
            $container->getLatitude(),
            $container->getLongitude(),
            $this->connection->quoteIdentifier('latitude'),
            $this->connection->quoteIdentifier('longitude')
        );

        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select($this->connection->quoteIdentifier('item_id'))
            ->from('tl_metamodel_geolocation')
            ->where($builder->expr()->lte($distanceCalculation, ':distance'))
            ->andWhere($builder->expr()->eq($this->connection->quoteIdentifier('att_id'), ':attributeID'))
            ->orderBy($distanceCalculation)
            ->setParameter('distance', $container->getDistance())
            ->setParameter('attributeID', $this->getMetaModel()->getAttribute($this->get('single_attr_id'))->get('id'));

        $statement = $builder->executeQuery();

        if (!$statement->rowCount()) {
            $filter->addFilterRule(new StaticIdList([]));
        } else {
            $filter->addFilterRule(new StaticIdList($statement->fetchFirstColumn()));
        }
    }

    /**
     * Run the search for the complex attribute geolocation.
     *
     * @param Container  $container     The container with all information.
     * @param IFilter    $filter        The filter container.
     * @param IAttribute $latAttribute  The attribute to filter on.
     * @param IAttribute $longAttribute The attribute to filter on.
     *
     * @return void
     *
     * @see https://www.movable-type.co.uk/scripts/latlong.html
     */
    protected function doSearchForTwoSimpleAtt($container, $filter, $latAttribute, $longAttribute)
    {
        // Calculate distance, bearing and more between Latitude/Longitude points
        $distanceCalculation = HaversineSphericalDistance::getFormulaAsQueryPart(
            $container->getLatitude(),
            $container->getLongitude(),
            $this->connection->quoteIdentifier($latAttribute->getColName()),
            $this->connection->quoteIdentifier($longAttribute->getColName())
        );

        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select($this->connection->quoteIdentifier('id'))
            ->from($this->connection->quoteIdentifier($this->getMetaModel()->getTableName()))
            ->where($builder->expr()->lte($distanceCalculation, ':distance'))
            ->orderBy($distanceCalculation)
            ->setParameter('distance', $container->getDistance());

        $statement = $builder->executeQuery();

        if (!$statement->rowCount()) {
            $filter->addFilterRule(new StaticIdList([]));
        } else {
            $filter->addFilterRule(new StaticIdList($statement->fetchFirstColumn()));
        }
    }

    /**
     * User the provider classes to make a look up.
     *
     * @param string $address The full address to search for.
     * @param string $country The country as 2-letters form.
     *
     * @return Container|null Return the container with all information or null on error.
     */
    protected function lookupGeo($address, $country)
    {
        // Trim the data. Better!
        $address = \trim($address);
        $country = \trim($country);

        // First check cache.
        $cacheResult = $this->getFromCache($address, $country);
        if (null !== $cacheResult) {
            return $cacheResult;
        }

        // If there is no data from the cache ask google.
        $lookupServices = (array) StringUtil::deserialize($this->get('lookupservice'), true);
        if (!\count($lookupServices)) {
            return null;
        }

        foreach ($lookupServices as $lookupService) {
            try {
                $callback = $this->getObjectFromName($lookupService['lookupservice']);

                // Call the main function.
                if (null !== $callback) {
                    /** @var Container $result */
                    $result = $callback
                        ->getCoordinates(
                            null,
                            null,
                            null,
                            $country,
                            $address,
                            $lookupService['apiToken'] ?: null
                        );

                    // Check if we have a result.
                    if (!$result->hasError()) {
                        $this->addToCache($address, $country, $result);

                        return $result;
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

        $reflectionName = $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'][$lookupClassName];

        $reflection = new \ReflectionClass($reflectionName);

        // Fetch singleton instance.
        if ($reflection->hasMethod('getInstance')) {
            $getInstanceMethod = $reflection->getMethod('getInstance');

            // Create a new instance.
            if ($getInstanceMethod->isStatic()) {
                return $getInstanceMethod->invoke(null);
            }

            return $reflection->newInstance();
        }

        // Create a normal object.
        return $reflection->newInstance();
    }

    /**
     * Add data to the cache.
     *
     * @param string    $address The address which where use for the search.
     * @param string    $country The country.
     * @param Container $result  The container with all information.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\DBALException When insert fails.
     */
    protected function addToCache($address, $country, $result)
    {
        $this->connection->insert(
            'tl_metamodel_perimetersearch',
            [
                $this->connection->quoteIdentifier('search')   => $address,
                $this->connection->quoteIdentifier('country')  => $country,
                $this->connection->quoteIdentifier('geo_lat')  => $result->getLatitude(),
                $this->connection->quoteIdentifier('geo_long') => $result->getLongitude()
            ]
        );
    }

    /**
     * Get data from cache.
     *
     * @param string $address The address which where use for the search.
     * @param string $country The country.
     *
     * @return Container|null
     */
    protected function getFromCache($address, $country)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from($this->connection->quoteIdentifier('tl_metamodel_perimetersearch'))
            ->where($builder->expr()->eq($this->connection->quoteIdentifier('search'), ':search'))
            ->andWhere($builder->expr()->eq($this->connection->quoteIdentifier('country'), ':country'))
            ->setParameter('search', $address)
            ->setParameter('country', $country);

        $statement = $builder->executeQuery();

        // If we have no data just return null.
        if (!$statement->rowCount()) {
            return null;
        }

        $result = $statement->fetchAssociative();

        // Build a new container.
        $container = new Container();
        $container->setLatitude($result['geo_lat']);
        $container->setLongitude($result['geo_long']);
        $container->setSearchParam(
            \strtr(
                $builder->getSQL(),
                [
                    ':search'  => $this->connection->quote($address),
                    ':country' => $this->connection->quote($country)
                ]
            )
        );

        return $container;
    }
}

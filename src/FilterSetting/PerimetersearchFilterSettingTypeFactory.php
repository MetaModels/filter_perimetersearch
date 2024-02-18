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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\FilterSetting;

use Doctrine\DBAL\Connection;
use MetaModels\Filter\FilterUrlBuilder;
use MetaModels\Filter\Setting\AbstractFilterSettingTypeFactory;
use MetaModels\Filter\Setting\ISimple;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Attribute type factory for text filter settings.
 */
class PerimetersearchFilterSettingTypeFactory extends AbstractFilterSettingTypeFactory
{
    /**
     * The Database connection.
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * The filter URL builder.
     *
     * @var FilterUrlBuilder
     */
    private FilterUrlBuilder $filterUrlBuilder;

    /**
     * Construct.
     *
     * @param EventDispatcherInterface $eventDispatcher  The event dispatcher.
     * @param Connection               $connection       The database connection.
     * @param FilterUrlBuilder         $filterUrlBuilder The filter URL builder.
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        Connection $connection,
        FilterUrlBuilder $filterUrlBuilder
    ) {
        parent::__construct();

        $this
            ->setTypeName('perimetersearch')
            ->setTypeIcon('bundles/metamodelsfilterperimetersearch/filter_perimetersearch.png')
            ->setTypeClass(Perimetersearch::class)
            ->allowAttributeTypes();

        foreach (
            [
                'geolocation',
                'text',
                'decimal'
            ] as $attribute
        ) {
            $this->addKnownAttributeType($attribute);
        }

        $this->eventDispatcher  = $eventDispatcher;
        $this->connection       = $connection;
        $this->filterUrlBuilder = $filterUrlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function createInstance($information, $filterSettings)
    {
        $typeClass = $this->getTypeClass();
        if ($typeClass === '' || $typeClass === null || !class_exists($typeClass)) {
            return null;
        }

        $typeObject = new $typeClass(
            $filterSettings,
            $information,
            $this->eventDispatcher,
            $this->connection,
            $this->filterUrlBuilder
        );
        assert($typeObject instanceof ISimple);

        return $typeObject;
    }
}

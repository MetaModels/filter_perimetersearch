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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;
use MetaModels\Filter\Setting\IFilterSettingFactory;

/**
 * This is the abstract base for the listeners.
 */
class Base
{
    /**
     * The filter factory to use.
     *
     * @var IFilterSettingFactory
     */
    protected $filterFactory;

    /**
     * Create a new instance.
     *
     * @param IFilterSettingFactory $filterFactory The filter setting factory.
     */
    public function __construct(IFilterSettingFactory $filterFactory)
    {
        $this->filterFactory = $filterFactory;
    }

    /**
     * Check if the current context is valid.
     *
     * @param GetPropertyOptionsEvent|GetOptionsEvent $event              The event.
     * @param string                                  $dataDefinitionName The allowed name of the data definition.
     * @param array                                   $properties         A list of allowed properties.
     *
     * @return bool
     */
    protected function isAllowedProperty($event, $dataDefinitionName, $properties)
    {
        if ($event->getEnvironment()->getDataDefinition()->getName() !== $dataDefinitionName) {
            return false;
        }

        if (!in_array($event->getPropertyName(), $properties)) {
            return false;
        }

        return true;
    }
}

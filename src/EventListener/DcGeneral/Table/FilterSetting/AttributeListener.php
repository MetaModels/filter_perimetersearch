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
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\ContainerInterface;
use MetaModels\Attribute\IAttribute;
use MetaModels\CoreBundle\Formatter\SelectAttributeOptionLabelFormatter;
use MetaModels\Filter\Setting\IFilterSettingFactory;

/**
 * This class provides the attribute options and encodes and decodes the attribute id.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AttributeListener extends Base
{
    /**
     * Allowed property names.
     *
     * @var string[]
     */
    private array $allowedProperties = ['first_attr_id', 'second_attr_id', 'single_attr_id'];

    /**
     * Allowed table name.
     *
     * @var string
     */
    private string $allowedTableName = 'tl_metamodel_filtersetting';

    /**
     * The attribute select option label formatter.
     *
     * @var SelectAttributeOptionLabelFormatter
     */
    private SelectAttributeOptionLabelFormatter $attributeLabelFormatter;

    /**
     * {@inheritDoc}
     *
     * @param SelectAttributeOptionLabelFormatter $attributeLabelFormatter The attribute select option label formatter.
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        IFilterSettingFactory $filterFactory,
        SelectAttributeOptionLabelFormatter $attributeLabelFormatter
    ) {
        parent::__construct($filterFactory);
        $this->attributeLabelFormatter = $attributeLabelFormatter;
    }

    /**
     * Provide options for default selection.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function getOptions(GetPropertyOptionsEvent $event)
    {
        // Check the context.
        if (
            !$this->isAllowedProperty($event, $this->allowedTableName, $this->allowedProperties)
        ) {
            return;
        }

        $result      = [];
        $model       = $event->getModel();
        $metaModel   = $this->filterFactory->createCollection($model->getProperty('fid'))->getMetaModel();
        $typeFactory = $this->filterFactory->getTypeFactory($model->getProperty('type'));

        $typeFilter = null;
        if ($typeFactory) {
            $typeFilter = $typeFactory->getKnownAttributeTypes();
        }

        foreach ($metaModel->getAttributes() as $attribute) {
            $typeName = (string) $attribute->get('type');

            if (\is_array($typeFilter) && (!\in_array($typeName, $typeFilter))) {
                continue;
            }

            $selectValue          = $attribute->getColName();
            $result[$selectValue] = $this->attributeLabelFormatter->formatLabel($attribute);
        }

        $event->setOptions($result);
    }

    /**
     * Translates an attribute id to a generated alias {@see getAttributeNames()}.
     *
     * @param DecodePropertyValueForWidgetEvent $event The event.
     *
     * @return void
     */
    public function decodeValue(DecodePropertyValueForWidgetEvent $event)
    {
        $dataDefinition = $event->getEnvironment()->getDataDefinition();
        assert($dataDefinition instanceof ContainerInterface);

        if (
            ($this->allowedTableName !== $dataDefinition->getName())
            || !\in_array($event->getProperty(), $this->allowedProperties, true)
        ) {
            return;
        }

        $model     = $event->getModel();
        $metaModel = $this->filterFactory->createCollection($model->getProperty('fid'))->getMetaModel();
        $value     = $event->getValue();

        if (!$value) {
            return;
        }

        $attribute = $metaModel->getAttributeById((int) $value);
        if ($attribute) {
            $event->setValue($metaModel->getTableName() . '_' . $attribute->getColName());
        }
    }

    /**
     * Translates a generated alias {@see getAttributeNames()} to the corresponding attribute id.
     *
     * @param EncodePropertyValueFromWidgetEvent $event The event.
     *
     * @return void
     */
    public function encodeValue(EncodePropertyValueFromWidgetEvent $event)
    {
        $dataDefinition = $event->getEnvironment()->getDataDefinition();
        assert($dataDefinition instanceof ContainerInterface);

        if (
            ($this->allowedTableName !== $dataDefinition->getName())
            || !\in_array($event->getProperty(), $this->allowedProperties)
        ) {
            return;
        }

        $model     = $event->getModel();
        $metaModel = $this->filterFactory->createCollection($model->getProperty('fid'))->getMetaModel();
        $value     = $event->getValue();

        if (!$value) {
            return;
        }

        $value = \substr($value, \strlen($metaModel->getTableName() . '_'));

        $attribute = $metaModel->getAttribute($value);
        assert($attribute instanceof IAttribute);

        $event->setValue($attribute->get('id'));
    }
}

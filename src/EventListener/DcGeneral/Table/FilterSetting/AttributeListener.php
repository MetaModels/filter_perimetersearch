<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2021 The MetaModels team.
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
 * @copyright  2012-2021 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use MetaModels\CoreBundle\Formatter\SelectAttributeOptionLabelFormatter;
use MetaModels\Filter\Setting\IFilterSettingFactory;

/**
 * This class provides the attribute options and encodes and decodes the attribute id.
 */
class AttributeListener extends Base
{
    /**
     * Allowed property names.
     *
     * @var string[]
     */
    private $allowedProperties = ['first_attr_id', 'second_attr_id', 'single_attr_id'];

    /**
     * Allowed table name.
     *
     * @var string
     */
    private $allowedTableName = 'tl_metamodel_filtersetting';

    /**
     * The attribute select option label formatter.
     *
     * @var SelectAttributeOptionLabelFormatter
     */
    private $attributeLabelFormatter;

    /**
     * {@inheritDoc}
     *
     * @param SelectAttributeOptionLabelFormatter $attributeLabelFormatter The attribute select option label formatter.
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
        if (!$this->isAllowedProperty($event, $this->allowedTableName, $this->allowedProperties)
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
            $typeName = $attribute->get('type');

            if ($typeFilter && (!\in_array($typeName, $typeFilter))) {
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
        if (!\in_array($event->getProperty(), $this->allowedProperties)
            || ($this->allowedTableName !== $event->getEnvironment()->getDataDefinition()->getName())
        ) {
            return;
        }

        $model     = $event->getModel();
        $metaModel = $this->filterFactory->createCollection($model->getProperty('fid'))->getMetaModel();
        $value     = $event->getValue();

        if (!($metaModel && $value)) {
            return;
        }

        $attribute = $metaModel->getAttributeById($value);
        if ($attribute) {
            $event->setValue($metaModel->getTableName() . '_' . $attribute->getColName());
        }
    }

    /**
     * Translates an generated alias {@see getAttributeNames()} to the corresponding attribute id.
     *
     * @param EncodePropertyValueFromWidgetEvent $event The event.
     *
     * @return void
     */
    public function encodeValue(EncodePropertyValueFromWidgetEvent $event)
    {
        if (!\in_array($event->getProperty(), $this->allowedProperties)
            || ($this->allowedTableName !== $event->getEnvironment()->getDataDefinition()->getName())
        ) {
            return;
        }

        $model     = $event->getModel();
        $metaModel = $this->filterFactory->createCollection($model->getProperty('fid'))->getMetaModel();
        $value     = $event->getValue();

        if (!($metaModel && $value)) {
            return;
        }

        $value = \substr($value, \strlen($metaModel->getTableName() . '_'));

        $attribute = $metaModel->getAttribute($value);

        if ($attribute) {
            $event->setValue($attribute->get('id'));
        }
    }
}

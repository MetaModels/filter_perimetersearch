<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage FilterPerimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL-3.0+
 * @filesource
 */

namespace MetaModels\DcGeneral\Events\Filter\Setting\Perimetersearch;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;
use MetaModels\BackendIntegration\TemplateList;
use MetaModels\DcGeneral\Events\BaseSubscriber;
use MetaModels\IMetaModel;

/**
 * Central event subscriber implementation.
 */
class Subscriber extends BaseSubscriber
{
    /**
     * {@inheritdoc}
     */
    protected function registerEventsInDispatcher()
    {
        $this
            ->addListener(
                GetPropertyOptionsEvent::NAME,
                array($this, 'getAttributeIdOptions')
            )
            ->addListener(
                GetOptionsEvent::NAME,
                array($this, 'getResolverClass')
            )
            ->addListener(
                GetPropertyOptionsEvent::NAME,
                array($this, 'getTemplateOptions')
            );
    }

    /**
     * Check if the current context is valid.
     *
     * @param GetPropertyOptionsEvent|GetOptionsEvent $event              The event.
     *
     * @param string                                  $dataDefinitionName The allowed name of the data definition.
     *
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

    /**
     * Retrieve the MetaModel attached to the model filter setting.
     *
     * @param ModelInterface $model The model for which to retrieve the MetaModel.
     *
     * @return IMetaModel
     */
    public function getMetaModel(ModelInterface $model)
    {
        $filterSetting = $this->getServiceContainer()->getFilterFactory()->createCollection($model->getProperty('fid'));

        return $filterSetting->getMetaModel();
    }

    /**
     * Prepares a option list with alias => name connection for all attributes.
     *
     * This is used in the attr_id select box.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function getAttributeIdOptions(GetPropertyOptionsEvent $event)
    {
        // Check the context.
        $allowedProperties = array('first_attr_id', 'second_attr_id', 'single_attr_id');
        if (!$this->isAllowedProperty($event, 'tl_metamodel_filtersetting', $allowedProperties)
        ) {
            return;
        }

        $result      = array();
        $model       = $event->getModel();
        $metaModel   = $this->getMetaModel($model);
        $typeFactory = $this
            ->getServiceContainer()
            ->getFilterFactory()
            ->getTypeFactory($model->getProperty('type'));

        $typeFilter = null;
        if ($typeFactory) {
            $typeFilter = $typeFactory->getKnownAttributeTypes();
        }

        if ($event->getPropertyName() === 'single_attr_id') {
            $typeFilter = array('geolocation');
        } else {
            $key = array_search('geolocation', $typeFilter);
            if ($key !== null) {
                unset($typeFilter[$key]);
            }
        }

        foreach ($metaModel->getAttributes() as $attribute) {
            $typeName = $attribute->get('type');
            if ($typeFilter && (!in_array($typeName, $typeFilter))) {
                continue;
            }
            $strSelectVal          = $attribute->getColName();
            $result[$strSelectVal] = $attribute->getName() . ' [' . $typeName . ']';
        }
        $event->setOptions($result);
    }

    /**
     * Get a list with all supported resolver class for a geo lookup.
     *
     * @param GetOptionsEvent $event The event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getResolverClass(GetOptionsEvent $event)
    {
        // Check the context.
        $allowedProperties = array('lookupservice');
        if (!$this->isAllowedProperty($event, 'tl_metamodel_filtersetting', $allowedProperties)
        ) {
            return;
        }

        // ToDo: Add a subscriber class for this.
        $arrClasses = (array) $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'];

        $arrReturn = array();
        foreach (array_keys($arrClasses) as $name) {
            $arrReturn[$name] = (isset($GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$name]))
                ? $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$name]
                : $name;
        }

        $event->setOptions($arrReturn);
    }

    /**
     * Provide options for default selection.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function getTemplateOptions(GetPropertyOptionsEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting')
            || ($event->getPropertyName() !== 'range_template')
        ) {
            return;
        }

        $list = new TemplateList();
        $list->setServiceContainer($this->getServiceContainer());
        $event->setOptions($list->getTemplatesForBase('mm_filteritem_'));
    }
}

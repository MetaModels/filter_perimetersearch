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
 * @subpackage FilterRange
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\DcGeneral\Events\Filter\Setting\Perimetersearch;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use MetaModels\BackendIntegration\TemplateList;
use MetaModels\DcGeneral\Events\BaseSubscriber;
use MetaModels\IMetaModel;

/**
 * Central event subscriber implementation.
 *
 * @package MetaModels\DcGeneral\Events\Filter\Setting\Range
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
                GetPropertyOptionsEvent::NAME,
                array($this, 'getResolverClass')
            )
            ->addListener(
                GetPropertyOptionsEvent::NAME,
                array($this, 'getTemplateOptions')
            );
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
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting')
            || !($event->getPropertyName() === 'first_attr_id' || $event->getPropertyName() === 'second_attr_id' || $event->getPropertyName() === 'single_attr_id')
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
        $typeFilter  = null;
        if ($typeFactory) {
            $typeFilter = $typeFactory->getKnownAttributeTypes();
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
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function getResolverClass(GetPropertyOptionsEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting')
            || !($event->getPropertyName() === 'lookupservice')
        ) {
            return;
        }

        \Controller::loadLanguageFile('tl_metamodel_filtersetting');

        $arrClasses = (array) $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'];

        $arrReturn = array();
        foreach ($arrClasses as $value)
        {
            $arrReturn[$value] = (isset($GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$value])) ? $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$value] : $value;
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
            || ($event->getPropertyName() !== 'range_template')) {
            return;
        }

        $list = new TemplateList();
        $list->setServiceContainer($this->getServiceContainer());
        $event->setOptions($list->getTemplatesForBase('mm_filteritem_'));
    }
}

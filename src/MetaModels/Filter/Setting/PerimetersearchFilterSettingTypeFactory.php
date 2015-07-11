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

namespace MetaModels\Filter\Setting;

/**
 * Attribute type factory for text filter settings.
 */
class PerimetersearchFilterSettingTypeFactory extends AbstractFilterSettingTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this
            ->setTypeName('perimetersearch')
            ->setTypeIcon('system/modules/metamodelsfilter_perimetersearch/html/filter_perimetersearch.png')
            ->setTypeClass('MetaModels\Filter\Setting\Perimetersearch')
            ->allowAttributeTypes();

        foreach (array(
                     'geolocation',
                     'text',
                     'decimal'
                 ) as $attribute) {
            $this->addKnownAttributeType($attribute);
        }
    }
}

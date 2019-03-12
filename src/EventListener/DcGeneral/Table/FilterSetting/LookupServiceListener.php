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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use MenAtWork\MultiColumnWizardBundle\Event\GetOptionsEvent;

/**
 * This class provides the attribute options and encodes and decodes the attribute id.
 */
class LookupServiceListener extends Base
{
    /**
     * Provide options for default selection.
     *
     * @param GetOptionsEvent $event The event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getOptions(GetOptionsEvent $event)
    {
        // Check the context.
        $allowedProperties = array('lookupservice', 'second_attr_id', 'single_attr_id');
        if (!$this->isAllowedProperty($event, 'tl_metamodel_filtersetting', $allowedProperties)
            || 'lookupservice' !== $event->getSubPropertyName()
        ) {
            return;
        }

        $arrClasses = (array) $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'];

        $arrReturn = [];
        foreach (\array_keys($arrClasses) as $name) {
            $arrReturn[$name] = ($GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$name] ?? $name);
        }

        $event->setOptions($arrReturn);
    }
}

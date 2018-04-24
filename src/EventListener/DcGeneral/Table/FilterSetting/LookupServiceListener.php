<?php

/**
 * This file is part of MetaModels/core.
 *
 * (c) 2012-2017 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/core/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;

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

        $arrReturn = array();
        foreach (array_keys($arrClasses) as $name) {
            $arrReturn[$name] = (isset($GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$name]))
                ? $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch'][$name]
                : $name;
        }

        $event->setOptions($arrReturn);
    }
}

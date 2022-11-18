<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2022 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/filter_perimetersearch
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener;

use ContaoCommunityAlliance\DcGeneral\Event\PrePersistModelEvent;

/**
 * Handles range selection.
 */
class SetDefaultRangeSelectionListener
{
    /**
     * Set default option in range selection.
     *
     * @param PrePersistModelEvent $event The event.
     *
     * @return void
     */
    public function __invoke(PrePersistModelEvent $event): void
    {
        if ('tl_metamodel_filtersetting' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        $model = $event->getModel();
        // Set 'defaultid' if is set 'isdefault'.
        if ('selection' !== $model->getProperty('rangemode')) {
            return;
        }

        $rangeSelection = [];
        $defaultIsSet   = false;
        foreach ((array) $model->getProperty('range_selection') as $option) {
            // Check 'isdefault' and clear all except the first one if more than one is checked.
            if ($option['isdefault']) {
                if ($defaultIsSet) {
                    $option['isdefault'] = '';
                } else {
                    $model->setProperty('defaultid', $option['range']);
                    $defaultIsSet = true;
                }
            }
            $rangeSelection[] = $option;
        }

        $model->setProperty('range_selection', $rangeSelection);
    }
}

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

use MetaModels\DcGeneral\Events\Filter\Setting\Perimetersearch\Subscriber;
use MetaModels\Events\MetaModelsBootEvent;
use MetaModels\Filter\Setting\Events\CreateFilterSettingFactoryEvent;
use MetaModels\Filter\Setting\PerimetersearchFilterSettingTypeFactory;
use MetaModels\MetaModelsEvents;

return [
    MetaModelsEvents::SUBSYSTEM_BOOT_BACKEND        => [
        function (MetaModelsBootEvent $event) {
            new Subscriber($event->getServiceContainer());
        }
    ],
    MetaModelsEvents::FILTER_SETTING_FACTORY_CREATE => [
        function (CreateFilterSettingFactoryEvent $event) {
            $event->getFactory()->addTypeFactory(new PerimetersearchFilterSettingTypeFactory());
        }
    ]
];

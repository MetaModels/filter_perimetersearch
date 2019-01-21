<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2017 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage FilterPerimetersearchBundle
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2012-2017 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

// This hack is to load the "old locations" of the classes.
use MetaModels\FilterPerimetersearchBundle\FilterSetting\Perimetersearch;
use MetaModels\FilterPerimetersearchBundle\FilterSetting\PerimetersearchFilterSettingTypeFactory;

spl_autoload_register(
    function ($class) {
        static $classes = [
            'MetaModels\Filter\Setting\Perimetersearch'                         => Perimetersearch::class,
            'MetaModels\Filter\Setting\PerimetersearchFilterSettingTypeFactory' => PerimetersearchFilterSettingTypeFactory::class,
        ];

        if (isset($classes[$class])) {
            // @codingStandardsIgnoreStart Silencing errors is discouraged
            @trigger_error('Class "' . $class . '" has been renamed to "' . $classes[$class] . '"', E_USER_DEPRECATED);
            // @codingStandardsIgnoreEnd

            if (!class_exists($classes[$class])) {
                spl_autoload_call($class);
            }

            class_alias($classes[$class], $class);
        }
    }
);
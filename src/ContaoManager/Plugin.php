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
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use MetaModels\CoreBundle\MetaModelsCoreBundle;
use MetaModels\FilterPerimetersearchBundle\MetaModelsFilterPerimetersearchBundle;

/**
 * Contao Manager plugin.
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(MetaModelsFilterPerimetersearchBundle::class)
                ->setLoadAfter(
                    [
                        MetaModelsCoreBundle::class
                    ]
                )
                ->setReplace(['metamodelsfilter_perimetersearch'])
        ];
    }
}

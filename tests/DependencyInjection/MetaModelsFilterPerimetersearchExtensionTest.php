<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2024 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/filter_perimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\Test\DependencyInjection;

use MetaModels\FilterPerimetersearchBundle\DependencyInjection\MetaModelsFilterPerimetersearchExtension;
use MetaModels\FilterPerimetersearchBundle\FilterSetting\PerimetersearchFilterSettingTypeFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * This test case test the extension.
 *
 * @SuppressWarnings(PHPMD.LongClassName)
 *
 * @covers \MetaModels\FilterPerimetersearchBundle\DependencyInjection\MetaModelsFilterPerimetersearchExtension
 */
class MetaModelsFilterPerimetersearchExtensionTest extends TestCase
{
    public function testInstantiation(): void
    {
        $extension = new MetaModelsFilterPerimetersearchExtension();

        $this->assertInstanceOf(MetaModelsFilterPerimetersearchExtension::class, $extension);
        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }

    public function testFactoryIsRegistered(): void
    {
        $container = new ContainerBuilder();

        $extension = new MetaModelsFilterPerimetersearchExtension();
        $extension->load([], $container);
        self::assertTrue($container->hasDefinition('metamodels.filter_perimetersearch.factory'));
        $definition = $container->getDefinition('metamodels.filter_perimetersearch.factory');
        self::assertCount(1, $definition->getTag('metamodels.filter_factory'));
    }
}

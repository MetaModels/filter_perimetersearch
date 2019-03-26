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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\Test\FilterHelper;

use MetaModels\FilterPerimetersearchBundle\FilterHelper\Container;
use MetaModels\FilterPerimetersearchBundle\FilterHelper\Coordinates;
use PHPUnit\Framework\TestCase;

/**
 * Test class for the coordinates filter.
 *
 * @covers \MetaModels\FilterPerimetersearchBundle\FilterHelper\Coordinates
 * @covers \MetaModels\FilterPerimetersearchBundle\Helper\SphericalDistance
 */
class CoordinatesTest extends TestCase
{
    public function dataProviderCoordinates()
    {
        return [
            [null, []],
            [null, [null, null, null, null, new \DateTime()]],
            [null, [null, null, null, null, '0,0,0']],
            [null, [null, null, null, null, '180,90']],

            [null, [null, null, null, null, '90,181']],
            [null, [null, null, null, null, '-90,-181']],
            [null, [null, null, null, null, '90,-181']],
            [null, [null, null, null, null, '-90,181']],

            [null, [null, null, null, null, '91,180']],
            [null, [null, null, null, null, '-91,-180']],
            [null, [null, null, null, null, '91,-180']],
            [null, [null, null, null, null, '-91,180']],

            [[90.0, 180.0], [null, null, null, null, '90,180']],
            [[-90.0, 180.0], [null, null, null, null, '-90,180']],
            [[-90.0, -180.0], [null, null, null, null, '-90,-180']],
            [[90.0, -180.0], [null, null, null, null, '90,-180']],

            [[89.123456789, 179.123456789], [null, null, null, null, '89.1234567890,179.1234567890']],
            [[-89.123456789, 179.123456789], [null, null, null, null, '-89.1234567890,179.1234567890']],
            [[-89.123456789, -179.123456789], [null, null, null, null, '-89.1234567890,-179.1234567890']],
            [[89.123456789, -179.123456789], [null, null, null, null, '89.1234567890,-179.1234567890']]
        ];
    }

    /**
     * @dataProvider dataProviderCoordinates
     */
    public function testGetCoordinates($expected, $params)
    {
        $lookupService = new Coordinates();

        /** @var \MetaModels\FilterPerimetersearchBundle\FilterHelper\Container $container */
        $container = \call_user_func_array([$lookupService, 'getCoordinates'], $params);
        if (null === $expected) {
            $this->assertNull($container);

            return;
        }

        $this->assertInstanceOf(Container::class, $container);
        $this->assertSame($container->getLatitude(), $expected[0]);
        $this->assertSame($container->getLongitude(), $expected[1]);
    }
}

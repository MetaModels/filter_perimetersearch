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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
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
        $exceptionMessage = [
            'The value full address is empty or not type of a string.',
            'The value full address has no two coordinates.',
            'The validation of the coordinates failed.'
        ];

        return [
            [[\RuntimeException::class, $exceptionMessage[0]], []],
            [[\RuntimeException::class, $exceptionMessage[1]], [null, null, null, null, 'sample text']],
            [[\RuntimeException::class, $exceptionMessage[1]], [null, null, null, null, '0']],
            [[\RuntimeException::class, $exceptionMessage[1]], [null, null, null, null, '0,0,0']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '180,90']],

            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '90,181']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '-90,-181']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '90,-181']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '-90,181']],

            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '91,180']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '-91,-180']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '91,-180']],
            [[\RuntimeException::class, $exceptionMessage[2]], [null, null, null, null, '-91,180']],

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
    public function testGetCoordinates(array $expected, array $params)
    {
        $lookupService = new Coordinates();

        if (\class_exists($expected[0]) && \in_array(\Exception::class, \class_parents($expected[0]))) {
            try {
                \call_user_func_array([$lookupService, 'getCoordinates'], $params);
            } catch (\Exception $exception) {
                $this->assertInstanceOf($expected[0], $exception);
                $this->assertSame($expected[1], $exception->getMessage());
            }

            return;
        }

        /** @var \MetaModels\FilterPerimetersearchBundle\FilterHelper\Container $container */
        $container = \call_user_func_array([$lookupService, 'getCoordinates'], $params);

        $this->assertInstanceOf(Container::class, $container);
        $this->assertSame($container->getLatitude(), $expected[0]);
        $this->assertSame($container->getLongitude(), $expected[1]);
    }
}

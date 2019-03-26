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

namespace MetaModels\FilterPerimetersearchBundle\Helper;

/**
 * This class provide functions for calculate haversine spherical distance.
 *
 * @see https://www.movable-type.co.uk/scripts/latlong.html
 * @see https://blog.godatadriven.com/impala-haversine.html
 */
class HaversineSphericalDistance extends SphericalDistance
{
    /**
     * Get the spherical distance haversine formula for the database query part.
     *
     * @param string|float $firstLatitude   The first latitude coordinate. You can a coordinate or a database field.
     * @param string|float $firstLongitude  The first longitude coordinate. You can a coordinate or a database field.
     * @param string|float $secondLatitude  The second latitude coordinate. You can a coordinate or a database field.
     * @param string|float $secondLongitude The second longitude coordinate. You can a coordinate or a database field.
     * @param int          $digits          The number of digits after the decimal point.
     * @param int          $earthRadius     The earth radius.
     *
     * @return string
     */
    public static function getFormulaAsQueryPart(
        $firstLatitude,
        $firstLongitude,
        $secondLatitude,
        $secondLongitude,
        $digits = 0,
        $earthRadius = self::EARTH_RADIUS_IN_KM
    ): string {
        return \sprintf(
            'ROUND(
                SQRT(
                    POWER(2 * PI() / 360 * (CAST(%1$s AS DECIMAL(9,6)) - CAST(%3$s AS DECIMAL(9,6))) * %6$s, 2) 
                    + POWER(2 * PI() / 360 * (CAST(%2$s AS DECIMAL(9,6)) - CAST(%4$s AS DECIMAL(9,6))) * %6$s 
                        * COS(2 * PI() / 360 * (CAST(%1$s AS DECIMAL(9,6)) + CAST(%3$s AS DECIMAL(9,6))) * 0.5), 2)
                ), %5$s
            )',
            $firstLatitude,
            $firstLongitude,
            $secondLatitude,
            $secondLongitude,
            $digits,
            $earthRadius
        );
    }

    /**
     * Calculate the spherical distance with the haversine formula.
     *
     * @param float $firstLatitude   The first latitude coordinate.
     * @param float $firstLongitude  The first longitude coordinate.
     * @param float $secondLatitude  The second latitude coordinate.
     * @param float $secondLongitude The second longitude coordinate.
     * @param int   $digits          The number of digits after the decimal point.
     * @param int   $earthRadius     The earth radius.
     *
     * @return float
     */
    public static function calculate(
        float $firstLatitude,
        float $firstLongitude,
        float $secondLatitude,
        float $secondLongitude,
        int $digits = 0,
        int $earthRadius = self::EARTH_RADIUS_IN_KM
    ): float {
        $oneRad = ((2 * M_PI) / 360);

        return \round(
            \sqrt(
                (($oneRad * ($firstLatitude - $secondLatitude) * $earthRadius) ** 2)
                + (($oneRad * ($firstLongitude - $secondLongitude) * $earthRadius
                    * \cos($oneRad * ($firstLatitude + $secondLatitude) * .5)) ** 2)
            ),
            $digits
        );
    }
}

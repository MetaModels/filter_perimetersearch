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

namespace MetaModels\FilterPerimetersearchBundle\FilterHelper;

use MetaModels\FilterPerimetersearchBundle\Helper\HaversineSphericalDistance;

/**
 * Lookup class for coordinates
 */
class Coordinates extends ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCoordinates(
        $street = null,
        $postal = null,
        $city = null,
        $country = null,
        $fullAddress = null,
        $apiToken = null
    ) {
        if (!$fullAddress || !\is_string($fullAddress)) {
            return null;
        }

        $coordinates = \explode(',', $fullAddress);
        if (!\count($coordinates) || (3 <= \count($coordinates))) {
            return null;
        }

        [$latitude, $longitude] = \array_map('floatval', $coordinates);
        if (!(HaversineSphericalDistance::validateLatitude($latitude)
            && HaversineSphericalDistance::validateLongitude($longitude))
        ) {
            return null;
        }

        return (new Container())
            ->setLatitude($latitude)
            ->setLongitude($longitude);
    }
}

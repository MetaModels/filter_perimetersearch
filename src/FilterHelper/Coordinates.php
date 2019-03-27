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
     *
     * @throws \RuntimeException Throws exception if the value full address is empty or not type of a string.
     * @throws \RuntimeException Throws exception if the value full address has no coordinates.
     * @throws \RuntimeException Throws exception if the validation of the coordinates failed.
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
            throw new \RuntimeException('The value full address is empty or not type of a string.');
        }

        $coordinates = \explode(',', $fullAddress);
        if (!\count($coordinates) || (3 <= \count($coordinates))) {
            throw new \RuntimeException('The value full address has no coordinates.');
        }

        [$latitude, $longitude] = \array_map('floatval', $coordinates);
        if (!(HaversineSphericalDistance::validateLatitude($latitude)
            && HaversineSphericalDistance::validateLongitude($longitude))
        ) {
            throw new \RuntimeException('The validation of the coordinates failed.');
        }

        return (new Container())
            ->setLatitude($latitude)
            ->setLongitude($longitude);
    }
}

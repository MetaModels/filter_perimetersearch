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

use MetaModels\FilterPerimetersearchBundle\FilterHelper\GoogleMaps;
use MetaModels\FilterPerimetersearchBundle\FilterHelper\OpenStreetMaps;

/**
 * Plugins for geo resolving.
 */
$GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class']['google_maps']      =
    GoogleMaps::class;
$GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class']['open_street_maps'] =
    OpenStreetMaps::class;

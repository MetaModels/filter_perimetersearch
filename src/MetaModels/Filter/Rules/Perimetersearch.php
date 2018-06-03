<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2018 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage Perimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\Filter\Rules;

use MetaModels\Attribute\IAttribute;
use MetaModels\Attribute\ISimple;
use MetaModels\Filter\IFilterRule;

/**
 * Rule for perimeter search.
 */
class Perimetersearch implements IFilterRule
{
    /**
     * The mode.
     *
     * @var int
     */
    protected $mode;

    /**
     * The attribute to filter on.
     *
     * @var IAttribute
     */
    protected $latitudeAttribute;

    /**
     * The attribute to filter on.
     *
     * @var IAttribute
     */
    protected $longitudeAttribute;

    /**
     * The attribute to filter on.
     *
     * @var IAttribute
     */
    protected $singleAttribute;

    /**
     * The latitude to search for.
     *
     * @var float|int
     */
    protected $latitude;

    /**
     * The longitude to search for.
     *
     * @var float|int
     */
    protected $longitude;

    /**
     * The dist for the search.
     *
     * @var int
     */
    protected $dist;

    /**
     * Single mode only for a geolocation attribute.
     */
    const MODE_SINGLE = 1;

    /**
     * Multimode for two simple attributes.
     */
    const MODE_MULTI = 2;

    /**
     * Create a new instance.
     *
     * @param IAttribute $latitudeAttribute  The attribute to perform filtering on.
     * @param IAttribute $longitudeAttribute The attribute to perform filtering on.
     * @param IAttribute $singleAttribute    The attribute to perform filtering on.
     * @param float|int  $lat                The latitude to search for.
     * @param float|int  $long               The longitude to search for.
     * @param int        $dist               The dist.
     *
     * @throws \InvalidArgumentException     If any value or attribute is not valid.
     */
    public function __construct($latitudeAttribute, $longitudeAttribute, $singleAttribute, $lat, $long, $dist)
    {
        // Check for valid attributes and values.
        $this->checkAttributeTypes($latitudeAttribute, $longitudeAttribute, $singleAttribute);
        $this->validateValue($lat, 'Only float and numeric allowed for the latitude.');
        $this->validateValue($long, 'Only float and numeric allowed for the longitude.');

        // Check if the dist value is valid.
        if (!\is_numeric($dist) || $dist < 0) {
            throw new \InvalidArgumentException('The dist has to be a valid number and greater than 0.');
        }

        // Set all vars.
        $this->latitudeAttribute  = $latitudeAttribute;
        $this->longitudeAttribute = $longitudeAttribute;
        $this->singleAttribute    = $singleAttribute;
        $this->latitude           = $lat;
        $this->longitude          = $long;
        $this->dist               = $dist;
    }

    /**
     * Retrieve the database.
     *
     * @return \Contao\Database
     */
    private function getDataBase()
    {
        $attribute = ($this->mode == self::MODE_SINGLE) ? $this->singleAttribute : $this->latitudeAttribute;

        return $attribute
            ->getMetaModel()
            ->getServiceContainer()
            ->getDatabase();
    }

    /**
     * Get the table name from the MetaModel.
     *
     * @return string
     */
    private function getMetaModelTableName()
    {
        $attribute = ($this->mode == self::MODE_SINGLE) ? $this->singleAttribute : $this->latitudeAttribute;

        return $attribute
            ->getMetaModel()
            ->getTableName();
    }

    /**
     * Check if the longitude value is valid.
     *
     * @param mixed  $value   The value to check.
     * @param string $message The exception message.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the value is not numeric or float.
     */
    private function validateValue($value, $message)
    {
        if (!\is_numeric($value) || !\is_float($value)) {
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * Check the attribute.
     *
     * @param IAttribute $latitudeAttribute  The attribute to be checked.
     * @param IAttribute $longitudeAttribute The attribute to be checked.
     * @param IAttribute $singleAttribute    The attribute to be checked.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If we have no single attribute or the lang/lot attribute is missing.
     */
    private function checkAttributeTypes($latitudeAttribute, $longitudeAttribute, $singleAttribute)
    {
        if ($singleAttribute !== null) {
            $this->checkMultiAttribute($singleAttribute);
            $this->mode = self::MODE_SINGLE;
            return;
        } elseif ($latitudeAttribute !== null && $longitudeAttribute !== null) {
            $this->checkSingleAttributes($latitudeAttribute, $longitudeAttribute);
            $this->mode = self::MODE_MULTI;
            return;
        }

        // If we have no hit throw an exception.
        throw new \InvalidArgumentException(
            'Need a pair of valid latitude and longitude attributes or a valid geolocation attribute.'
        );
    }

    /**
     * Check the multi attribute.
     *
     * @param IAttribute $singleAttribute The attribute to be checked.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the attribute is not from type geolocation.
     */
    private function checkMultiAttribute($singleAttribute)
    {
        // Check the multi mode attribute type.
        if ($singleAttribute->get('type') !== 'geolocation') {
            throw new \InvalidArgumentException('Only a geolocation attribute is supported for the single mode.');
        }
    }

    /**
     * Check the single attributes.
     *
     * @param IAttribute $latitudeAttribute  The attribute to be checked.
     * @param IAttribute $longitudeAttribute The attribute to be checked.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If one of the attribute is not from type ISimple.
     */
    private function checkSingleAttributes($latitudeAttribute, $longitudeAttribute)
    {
        // Check if both of the are simple
        if (!($latitudeAttribute instanceof ISimple) || !($longitudeAttribute instanceof ISimple)) {
            throw new \InvalidArgumentException('Only simple attributes are allowed.');
        }

        if ($latitudeAttribute->getMetaModel() !== $longitudeAttribute->getMetaModel()) {
            throw new \InvalidArgumentException('The first and second attribute have to be from the same MetaModel.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMatchingIds()
    {
        if ($this->mode == self::MODE_SINGLE) {
            return $this->runSimpleQuery(
                'item_id',
                'tl_metamodel_geolocation',
                'latitude',
                'longitude',
                ['att_id=?' => $this->singleAttribute->get('id')]
            );
        } else {
            return $this->runSimpleQuery(
                'id',
                $this->getMetaModelTableName(),
                $this->latitudeAttribute->getColName(),
                $this->longitudeAttribute->getColName(),
                null
            );
        }
    }

    /**
     * Build the SQL and execute it.
     *
     * @param string $idField         Name of the id field.
     * @param string $tableName       The name of the table.
     * @param string $latitudeField   The name of the latitude field.
     * @param string $longitudeField  The name of the longitude field.
     * @param array  $additionalWhere A list with additional where information.
     *
     * @return array A list with ID's or an empty array.
     */
    protected function runSimpleQuery($idField, $tableName, $latitudeField, $longitudeField, $additionalWhere)
    {
        // Base SQL with place holders.
        $strSelect = 'SELECT %5$s ' .
            'FROM %1$s ' .
            'WHERE
                %4$s
                round(
                    sqrt(
                        power( 2 * pi() / 360 * (? - %2$s) * 6371,2) +
                        power( 2 * pi() / 360 * (? - %3$s) * 6371 * COS( 2 * pi() / 360 * (? + %2$s) * 0.5 ),2)
                    )
                ) <= ? ' .
            'ORDER BY
                round(
                    sqrt(
                        power(2 * pi() / 360 * (? - %2$s) * 6371,2) +
                        power(2 * pi() / 360 * (? - %3$s) * 6371 *  COS( 2 * pi() / 360 * (? + %2$s) * 0.5 ),2)))';

        // First value set for save values.
        // @codingStandardsIgnoreStart
        $strSelect = \sprintf(
            $strSelect,
            $tableName, // 1
            $latitudeField, // 2
            $longitudeField, // 3
            $this->buildAdditionalWhere($additionalWhere), // 4
            $idField // 5
        );
        // @codingStandardsIgnoreEnd

        // Second value set for the database query.
        $lat    = $this->latitude;
        $lng    = $this->longitude;
        $dist   = $this->dist;
        $values = \array_merge(
            (array) $additionalWhere,
            [$lat, $lng, $lat, $dist, $lat, $lng, $lat]
        );

        $objResult = $this
            ->getDataBase()
            ->prepare($strSelect)
            ->execute($values);

        // Check the data.
        if ($objResult->numRows == 0) {
            return [];
        } else {
            return $objResult->fetchEach($idField);
        }
    }

    /**
     * Build a where ...
     *
     * @param array $additionalWhere A list with additional where information.
     *
     * @return null|string
     */
    protected function buildAdditionalWhere($additionalWhere)
    {
        if ($additionalWhere === null) {
            return null;
        }

        $sql = \implode(' AND ', \array_keys((array) $additionalWhere));

        return \strlen($sql) ? $sql . ' AND ' : null;
    }
}

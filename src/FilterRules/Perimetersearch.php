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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace  MetaModels\FilterPerimetersearchBundle\FilterRules;

use Contao\System;
use Doctrine\DBAL\Connection;
use MetaModels\Attribute\IAttribute;
use MetaModels\Attribute\ISimple;
use MetaModels\Filter\IFilterRule;
use MetaModels\FilterPerimetersearchBundle\Helper\HaversineSphericalDistance;

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
    public const MODE_SINGLE = 1;

    /**
     * Multimode for two simple attributes.
     */
    public const MODE_MULTI = 2;

    /**
     * Database connection.
     *
     * @var Connection|null
     */
    private Connection|null $connection;

    /**
     * Create a new instance.
     *
     * @param IAttribute      $latitudeAttribute  The attribute to perform filtering on.
     * @param IAttribute      $longitudeAttribute The attribute to perform filtering on.
     * @param IAttribute      $singleAttribute    The attribute to perform filtering on.
     * @param float|int       $lat                The latitude to search for.
     * @param float|int       $long               The longitude to search for.
     * @param int             $dist               The dist.
     * @param Connection|null $connection         The database connection.
     *
     * @throws \InvalidArgumentException     If any value or attribute is not valid.
     */
    public function __construct(
        $latitudeAttribute,
        $longitudeAttribute,
        $singleAttribute,
        $lat,
        $long,
        $dist,
        Connection $connection = null
    ) {
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

        if (null === $connection) {
            // @codingStandardsIgnoreStart
            @\trigger_error(
                'Connection is not passed as constructor argument.',
                E_USER_DEPRECATED
            );
            // @codingStandardsIgnoreEnd
            $connection = System::getContainer()->get('database_connection');
        }

        $this->connection = $connection;
    }

    /**
     * Get the table name from the MetaModel.
     *
     * @return string
     */
    private function getMetaModelTableName()
    {
        $attribute = ($this->mode === self::MODE_SINGLE) ? $this->singleAttribute : $this->latitudeAttribute;

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
        if (null !== $singleAttribute) {
            $this->checkMultiAttribute($singleAttribute);
            $this->mode = self::MODE_SINGLE;
            return;
        }

        if ((null !== $latitudeAttribute) && (null !== $longitudeAttribute)) {
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
        if ('geolocation' !== $singleAttribute->get('type')) {
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
        if ($this->mode === self::MODE_SINGLE) {
            return $this->runSimpleQuery(
                'item_id',
                'tl_metamodel_geolocation',
                'latitude',
                'longitude',
                ['att_id=?' => $this->singleAttribute->get('id')]
            );
        }

        return $this->runSimpleQuery(
            'id',
            $this->getMetaModelTableName(),
            $this->latitudeAttribute->getColName(),
            $this->longitudeAttribute->getColName(),
            null
        );
    }

    /**
     * Build the SQL and execute it.
     *
     * @param string     $idField         Name of the id field.
     * @param string     $tableName       The name of the table.
     * @param string     $latitudeField   The name of the latitude field.
     * @param string     $longitudeField  The name of the longitude field.
     * @param array|null $additionalWhere A list with additional where information.
     *
     * @return array A list with ID's or an empty array.
     */
    protected function runSimpleQuery($idField, $tableName, $latitudeField, $longitudeField, $additionalWhere)
    {
        $distanceCalculation = HaversineSphericalDistance::getFormulaAsQueryPart(
            $this->latitude,
            $this->longitude,
            $this->connection->quoteIdentifier($latitudeField),
            $this->connection->quoteIdentifier($longitudeField),
            2
        );

        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select($this->connection->quoteIdentifier($idField))
            ->from($tableName)
            ->where($builder->expr()->lte($distanceCalculation, ':distance'))
            ->orderBy($distanceCalculation)
            ->setParameter('distance', $this->dist);

        if ($additionalWhere) {
            foreach ($additionalWhere as $index => $where) {
                if (0 === $index) {
                    $builder->where($where);
                }

                $builder->andWhere($where);
            }

            $builder->andWhere($builder->expr()->lte($distanceCalculation, ':distance'));
        } else {
            $builder->where($builder->expr()->lte($distanceCalculation, ':distance'));
        }

        $statement = $builder->executeQuery();
        if (!$statement->rowCount()) {
            return [];
        }

        return $statement->fetchFirstColumn();
    }

    /**
     * Build a where ...
     *
     * @param array $additionalWhere A list with additional where information.
     *
     * @return null|string
     *
     * @deprecated This is deprecated since 2.1 and where remove in 3.0.
     */
    protected function buildAdditionalWhere($additionalWhere)
    {
        if (null === $additionalWhere) {
            return null;
        }

        $sql = \implode(' AND ', \array_keys($additionalWhere));

        return ('' !== $sql) ? $sql . ' AND ' : null;
    }
}

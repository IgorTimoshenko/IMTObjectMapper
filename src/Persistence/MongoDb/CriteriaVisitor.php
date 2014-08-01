<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence\MongoDb;

use IMT\ObjectMapper\Exception\LogicException;
use IMT\ObjectMapper\CriteriaInterface;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class CriteriaVisitor
{
    /**
     * @var array
     */
    private $comparisonMap = array(
        CriteriaInterface::COMPARISON_GT    => '$gt',
        CriteriaInterface::COMPARISON_GTE   => '$gte',
        CriteriaInterface::COMPARISON_LT    => '$lt',
        CriteriaInterface::COMPARISON_LTE   => '$lte',
        CriteriaInterface::COMPARISON_NE    => '$ne',
        CriteriaInterface::COMPARISON_REGEX => '$regex',
    );

    /**
     * @param  CriteriaInterface $criteria
     * @return array
     * @throws LogicException              If a comparison is not supported
     */
    public function visitClause(CriteriaInterface $criteria)
    {
        $query = array();

        foreach ($criteria->getClauses() as $field => $clause) {
            list($comparison, $value, $type) = $clause;

            if ($value instanceof CriteriaInterface) {
                $nestedQuery = $this->visitClause($value);

                foreach ($nestedQuery as $nestedField => $nestedValue) {
                    $query[$field . '.' . $nestedField] = Type\Type::getType($type)->toDatabase($nestedValue);
                }

                continue;
            }

            $value = Type\Type::getType($type)->toDatabase($value);

            if ($comparison) {
                if (!isset($this->comparisonMap[$comparison])) {
                    throw new LogicException("The `$comparison` comparison is not supported.");
                }

                if ($comparison === CriteriaInterface::COMPARISON_REGEX) {
                    $value = new \MongoRegex($value);
                }

                $value = array($this->comparisonMap[$comparison] => $value);
            }

            $query[$field] = $value;
        }

        return $query;
    }
}

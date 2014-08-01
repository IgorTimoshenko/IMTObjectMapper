<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 * @codeCoverageIgnore
 */
interface CriteriaInterface
{
    const COMPARISON_GT    = '>';
    const COMPARISON_GTE   = '>=';
    const COMPARISON_LT    = '<';
    const COMPARISON_LTE   = '<=';
    const COMPARISON_NE    = '!=';
    const COMPARISON_REGEX = 'regex';

    const SORT_ASC  = 1;
    const SORT_DESC = -1;

    /**
     * @param  string      $field
     * @param  mixed       $value
     * @param  null|string $comparison
     * @param  string      $type
     * @return Criteria
     */
    public function addClause($field, $value, $comparison = null, $type = null);

    /**
     * @return array
     */
    public function getClauses();

    /**
     * @param  string $field
     * @param  mixed  $value
     */
    public function addSort($field, $value);

    /**
     * @return array
     */
    public function getSort();

    /**
     * @param integer $limit
     */
    public function setLimit($limit);

    /**
     * @return integer
     */
    public function getLimit();
}

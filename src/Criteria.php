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
 */
class Criteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $clauses = array();

    /**
     * @var array
     */
    private $sort    = array();

    /**
     * @var integer
     */
    private $limit;

    /**
     * {@inheritDoc}
     */
    public function addClause($field, $value, $comparison = null, $type = null)
    {
        $this->clauses[$field] = array($comparison, $value, $type);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getClauses()
    {
        return $this->clauses;
    }

    /**
     * {@inheritDoc}
     */
    public function addSort($field, $value)
    {
        $this->sort[$field] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * {@inheritDoc}
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
    }

    /**
     * {@inheritDoc}
     */
    public function getLimit()
    {
        return $this->limit;
    }
}

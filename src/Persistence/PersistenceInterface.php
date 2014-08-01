<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence;

use IMT\ObjectMapper\CriteriaInterface;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 * @codeCoverageIgnore
 */
interface PersistenceInterface
{
    /**
     * @param  integer    $id
     * @param  array      $metadata
     * @return null|array
     */
    public function findById($id, array $metadata);

    /**
     * @param  CriteriaInterface $criteria
     * @param  array             $metadata
     * @return null|array
     */
    public function findOneByCriteria(CriteriaInterface $criteria, array $metadata);

    /**
     * @param  CriteriaInterface $criteria
     * @param  array             $metadata
     * @return \Iterator
     */
    public function findByCriteria(CriteriaInterface $criteria, array $metadata);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param  array      $data
     * @param  array      $metadata
     * @return null|array
     */
    public function persist(array $data, array $metadata);

    /**
     * @param  integer $id
     * @param  array   $metadata
     * @return boolean
     */
    public function remove($id, array $metadata);
}

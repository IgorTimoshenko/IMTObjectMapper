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
class EntityRepository
{
    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     * @param string        $entityClass
     */
    public function __construct(EntityManager $entityManager, $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass   = $entityClass;
    }

    /**
     * @param  string      $id
     * @return null|object
     */
    public function findById($id)
    {
        return $this->entityManager->findById($this->entityClass, $id);
    }

    /**
     * @param  Criteria    $criteria
     * @return null|object
     */
    public function findOneByCriteria(Criteria $criteria)
    {
        return $this->entityManager->findOneByCriteria($this->entityClass, $criteria);
    }

    /**
     * @param  Criteria $criteria
     * @return object[]
     */
    public function findByCriteria(Criteria $criteria)
    {
        return $this->entityManager->findByCriteria($this->entityClass, $criteria);
    }

    /**
     * @return object[]
     */
    public function findByEmptyCriteria()
    {
        return $this->findByCriteria(new Criteria());
    }
}

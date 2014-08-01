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
class EntityManager
{
    /**
     * @var Persistence\PersistenceInterface
     */
    private $persistence;

    /**
     * @var object[]
     */
    private $repositories = array();

    /**
     * @var Persistence\AbstractHydrator[]
     */
    private $hydrators = array();

    /**
     * @var array
     */
    private $metadata  = array();

    /**
     * @param Persistence\PersistenceInterface $persistence
     */
    public function __construct(Persistence\PersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param  string $class
     * @return EntityRepository
     * @throws Exception\RuntimeException If the repository does not exist
     */
    public function getRepository($class)
    {
        if (!isset($this->repositories[$class])) {
            $metadata = $this->getMetadata($class);

            if (!isset($metadata['repositoryClass'])) {
                $repositoryClass = $class . 'Repository';
            } else {
                $repositoryClass = $metadata['repositoryClass'];
            }

            if (!@class_exists($repositoryClass)) {
                $repositoryClass = __NAMESPACE__ . '\\EntityRepository';
            }

            $this->repositories[$class] = new $repositoryClass($this, $class);
        }

        return $this->repositories[$class];
    }

    /**
     * @param Persistence\AbstractHydrator $hydrator
     */
    public function registerHydrator(Persistence\AbstractHydrator $hydrator)
    {
        $this->hydrators[$hydrator->getName()] = $hydrator;
    }

    /**
     * @param array $metadata
     */
    public function registerMetadata(array $metadata)
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }

    /**
     * @param  string      $entityClass
     * @param  mixed       $id
     * @return null|object
     */
    public function findById($entityClass, $id)
    {
        $metadata = $this->getMetadata($entityClass);

        if ($data = $this->persistence->findById($id, $metadata)) {
            return $this->getHydrator($this->persistence->getName())->hydrate($data, $entityClass);
        }

        return $data;
    }

    /**
     * @param  string            $entityClass
     * @param  CriteriaInterface $criteria
     * @return null|object
     */
    public function findOneByCriteria($entityClass, CriteriaInterface $criteria)
    {
        $metadata = $this->getMetadata($entityClass);

        if ($data = $this->persistence->findOneByCriteria($criteria, $metadata)) {
            return $this->getHydrator($this->persistence->getName())->hydrate($data, $entityClass);
        }

        return $data;
    }

    /**
     * @param  string            $entityClass
     * @param  CriteriaInterface $criteria
     * @return null|object[]
     */
    public function findByCriteria($entityClass, CriteriaInterface $criteria)
    {
        $metadata = $this->getMetadata($entityClass);

        return new Persistence\ResultSet(
            $this->persistence->findByCriteria($criteria, $metadata),
            $this->getHydrator($this->persistence->getName()),
            $entityClass
        );
    }

    /**
     * @param  object $entity
     * @throws Exception\InvalidArgumentException If the argument is not an object
     * @return boolean
     */
    public function persist($entity)
    {
        if (!is_object($entity)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Expected argument of type object, %s given.', gettype($entity))
            );
        }

        $data = $this->persistence->persist(
            $this->getHydrator($this->persistence->getName())->extract($entity),
            $this->getMetadata(get_class($entity))
        );

        if (!$entity->getId() && isset($data['id'])) {
            $entityReflection = new \ReflectionObject($entity);
            $propertyReflection = $entityReflection->getProperty('id');
            $propertyReflection->setAccessible(true);
            $propertyReflection->setValue($entity, $data['id']);
        }

        return $data !== null;
    }

    /**
     * @param  object $entity
     * @throws Exception\InvalidArgumentException If the argument is not an object
     * @return boolean
     */
    public function remove($entity)
    {
        if (!is_object($entity)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Expected argument of type object, %s given.', gettype($entity))
            );
        }

        return $this->persistence->remove($entity->getId(), $this->getMetadata(get_class($entity)));
    }

    /**
     * @param  string $name
     * @return Persistence\AbstractHydrator
     * @throws Exception\RuntimeException
     */
    private function getHydrator($name)
    {
        if (!isset($this->hydrators[$name])) {
            throw new Exception\RuntimeException("The `$name` hydrator is not registered.");
        }

        return $this->hydrators[$name];
    }

    /**
     * @param  string $class
     * @return array
     * @throws Exception\RuntimeException If the entity is not registered
     */
    private function getMetadata($class)
    {
        if (!isset($this->metadata[$this->persistence->getName()][$class])) {
            throw new Exception\RuntimeException("The `$class` entity is not registered.");
        }

        return $this->metadata[$this->persistence->getName()][$class];
    }
}

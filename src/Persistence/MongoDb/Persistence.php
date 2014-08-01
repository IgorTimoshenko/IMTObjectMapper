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

use IMT\ObjectMapper\Persistence\PersistenceInterface;
use IMT\ObjectMapper\Exception\LogicException;
use IMT\ObjectMapper\Criteria;
use IMT\ObjectMapper\CriteriaInterface;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class Persistence implements PersistenceInterface
{
    /**
     * @var CriteriaVisitor
     */
    private $criteriaVisitor;

    /**
     * @var \MongoDB
     */
    private $mongoDb;

    /**
     * @param \MongoDB        $mongoDb
     * @param CriteriaVisitor $criteriaVisitor
     */
    public function __construct(\MongoDB $mongoDb, CriteriaVisitor $criteriaVisitor)
    {
        $this->mongoDb         = $mongoDb;
        $this->criteriaVisitor = $criteriaVisitor;
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id, array $metadata)
    {
        $criteria = new Criteria();
        $criteria->addClause('_id', $id, null, 'id');

        return $this->findOneByCriteria($criteria, $metadata);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneByCriteria(CriteriaInterface $criteria, array $metadata)
    {
        $collection = $this->getCollectionName($metadata);

        try {
            if ($data = $this->mongoDb->$collection->findOne($this->criteriaVisitor->visitClause($criteria))) {
                $data['id'] = (string) $data['_id'];
                unset($data['_id']);
            }

            return $data;
        } catch (\MongoException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findByCriteria(CriteriaInterface $criteria, array $metadata)
    {
        $collection = $this->getCollectionName($metadata);

        try {
            $mongoCursor = $this->mongoDb->$collection
                ->find($this->criteriaVisitor->visitClause($criteria))
                ->immortal(true);
        } catch (\MongoException $e) {
            return new \EmptyIterator();
        }

        if ($criteria->getSort()) {
            $mongoCursor->sort($criteria->getSort());
        }

        if ($criteria->getLimit()) {
            $mongoCursor->limit($criteria->getLimit());
        }

        return new ResultSet($mongoCursor);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mongo_db';
    }

    /**
     * {@inheritDoc}
     */
    public function persist(array $data, array $metadata)
    {
        $collection = $this->getCollectionName($metadata);

        try {
            if (!isset($data['id'])) {
                unset($data['id']); // null

                $this->mongoDb->$collection->insert($data);
            } else {
                $data['_id'] = $data['id'];
                unset($data['id']);

                $this->mongoDb->$collection->update(array('_id' => $data['_id']), $data);
            }

            $data['id'] = (string) $data['_id'];
            unset($data['_id']);

            return $data;
        } catch (\MongoException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function remove($id, array $metadata)
    {
        $collection = $this->getCollectionName($metadata);

        try {
            return $this->mongoDb->$collection->remove(array('_id' => new \MongoId($id)));
        } catch (\MongoException $e) {
            return false;
        }
    }

    /**
     * @param  array          $metadata
     * @return string
     * @throws LogicException           If the collection name is not specified
     */
    private function getCollectionName(array $metadata)
    {
        if (!isset($metadata['collection'])) {
            throw new LogicException("The collection name is not specified.");
        }

        return $metadata['collection'];
    }
}

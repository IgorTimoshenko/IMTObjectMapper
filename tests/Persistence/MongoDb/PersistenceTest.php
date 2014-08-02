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

use IMT\ObjectMapper\Criteria;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class PersistenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \MongoDb
     */
    private static $mongoDb;

    /**
     * @var array
     */
    private $metadata = array(
        'collection' => 'c',
    );

    /**
     * @var Persistence
     */
    private $persistence;

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $mongoDbFactory = new MongoDbFactory();

        self::$mongoDb = $mongoDbFactory->create(
            '127.0.0.1',
            27017,
            '',
            '',
            'test',
            array(
                'connectTimeoutMS' => 2000,
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        self::$mongoDb->c->drop();
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->persistence = new Persistence(self::$mongoDb, new CriteriaVisitor());

        self::$mongoDb->c->remove(array());
    }

    public function testFindByIsNotExists()
    {
        $this->assertNull($this->persistence->findById(100500, $this->metadata));
    }

    public function testFindById()
    {
        $document = $this->insertDocument(array('f' => 'v'));

        $this->assertEquals($document, $this->persistence->findById($document['id'], $this->metadata));
    }

    public function testFindOneByCriteriaInvalidMetadata()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\LogicException',
            'The collection name is not specified.'
        );

        $this->persistence->findOneByCriteria(new Criteria(), array());
    }

    public function testFindOneByCriteriaException()
    {
        $persistence = new Persistence($this->getMongoDbMock('findOne'), new CriteriaVisitor());

        $this->assertNull($persistence->findOneByCriteria(new Criteria(), $this->metadata));
    }

    public function testFindOneByCriteriaNotExists()
    {
        $this->assertNull($this->persistence->findOneByCriteria(new Criteria(), $this->metadata));
    }

    public function testFindOneByCriteria()
    {
        $document = $this->insertDocument(array('f' => 'v'));

        $criteria = new Criteria();
        $criteria->addClause('f', 'v');

        $this->assertEquals($document, $this->persistence->findOneByCriteria($criteria, $this->metadata));
    }

    public function testFindByCriteriaInvalidMetadata()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\LogicException',
            'The collection name is not specified.'
        );

        $this->persistence->findByCriteria(new Criteria(), array());
    }

    public function testFindByCriteriaException()
    {
        $persistence = new Persistence($this->getMongoDbMock('find'), new CriteriaVisitor());

        $this->assertInstanceOf('EmptyIterator', $persistence->findByCriteria(new Criteria(), $this->metadata));
    }

    public function testFindByCriteriaNotExists()
    {
        $criteria = new Criteria();
        $criteria->addClause('f', 0, Criteria::COMPARISON_GT);

        $data = $this->persistence->findByCriteria($criteria, $this->metadata);

        $this->assertInstanceOf('IMT\ObjectMapper\Persistence\MongoDb\ResultSet', $data);
        $this->assertCount(0, $data);
    }

    public function testFindByCriteria()
    {
        $documents = $this->insertDocuments(
            array(
                array('f' => 1),
                array('f' => 2),
                array('f' => 3),
            )
        );

        $criteria = new Criteria();
        $criteria->addClause('f', 0, Criteria::COMPARISON_GT);

        $this->assertEquals(
            $documents,
            iterator_to_array($this->persistence->findByCriteria($criteria, $this->metadata))
        );
    }

    public function testFindByCriteriaSort()
    {
        $documents = $this->insertDocuments(
            array(
                array('f1' => 'v1', 'f2' => 1),
                array('f1' => 'v1', 'f2' => 2),
                array('f1' => 'v1', 'f3' => 3),
            )
        );

        $criteria = new Criteria();
        $criteria->addClause('f1', 'v1');

        $criteria->addSort('f2', Criteria::SORT_DESC);

        $this->assertEquals(
            array_reverse($documents),
            iterator_to_array($this->persistence->findByCriteria($criteria, $this->metadata))
        );
    }

    public function testFindByCriteriaLimit()
    {
        $documents = $this->insertDocuments(
            array(
                array('f1' => 'v1', 'f2' => 1),
                array('f1' => 'v1', 'f2' => 2),
                array('f1' => 'v1', 'f2' => 3),
            )
        );

        $criteria = new Criteria();
        $criteria->addClause('f1', 'v1');

        $criteria->setLimit(2);

        $this->assertEquals(
            array_slice($documents, 0, 2),
            iterator_to_array($this->persistence->findByCriteria($criteria, $this->metadata))
        );
    }

    public function testGetName()
    {
        $this->assertEquals('mongo_db', $this->persistence->getName());
    }

    public function testPersistInvalidMetadata()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\LogicException',
            'The collection name is not specified.'
        );

        $this->persistence->persist(array(), array());
    }

    public function testPersistInsertException()
    {
        $persistence = new Persistence($this->getMongoDbMock('insert'), new CriteriaVisitor());

        $this->assertNull($persistence->persist(array('f' => 'v'), $this->metadata));
    }

    public function testPersistInsert()
    {
        $data = array('f' => 'v');

        $result = $this->persistence->persist($data, $this->metadata);

        $this->assertEquals(1, self::$mongoDb->c->count($data));
        $this->assertArrayHasKey('id', $result);
    }

    public function testPersistUpdateException()
    {
        $persistence = new Persistence($this->getMongoDbMock('update'), new CriteriaVisitor());

        $this->assertNull($persistence->persist(array('id' => 100500, 'f' => 'v'), $this->metadata));
    }

    public function testPersistUpdate()
    {
        $document = $this->insertDocument(array('f' => 'v'));
        $document['f'] = 'v2';

        $this->assertEquals($document, $this->persistence->persist($document, $this->metadata));
    }

    public function testRemoveInvalidMetadata()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\LogicException',
            'The collection name is not specified.'
        );

        $this->persistence->remove('', array());
    }

    public function testRemoveException()
    {
        $persistence = new Persistence($this->getMongoDbMock('remove'), new CriteriaVisitor());

        $this->assertFalse($persistence->remove(100500, $this->metadata));
    }

    public function testRemove()
    {
        $document = $this->insertDocument(array('f' => 'v'));

        $returnStatement = $this->persistence->remove($document['id'], $this->metadata);

        $this->assertInternalType('array', $returnStatement);
        $this->assertArrayHasKey('ok', $returnStatement);
        $this->assertEquals('1.0', $returnStatement);
    }

    /**
     * @param  array $document
     * @return array
     */
    private function insertDocument(array $document)
    {
        self::$mongoDb->c->insert($document);
        $document['id'] = (string) $document['_id'];
        unset($document['_id']);

        return $document;
    }

    /**
     * @param  array $documents
     * @return array
     */
    private function insertDocuments(array $documents)
    {
        foreach ($documents as $key => $document) {
            self::$mongoDb->c->insert($document);

            $id = (string) $document['_id'];
            $document['id'] = $id;
            unset($document['_id']);
            $documents[$id] = $document;
            unset($documents[$key]);
        }

        return $documents;
    }

    /**
     * @param  string   $methodName
     * @return \MongoDb
     */
    private function getMongoDbMock($methodName)
    {
        $mongoCollection = $this
            ->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $mongoCollection
            ->expects($this->once())
            ->method($methodName)
            ->will($this->throwException(new \MongoException()));

        $mongoDb = $this
            ->getMockBuilder('MongoDb')
            ->disableOriginalConstructor()
            ->getMock();
        $mongoDb
            ->expects($this->once())
            ->method('__get')
            ->with($this->equalTo('c'))
            ->will($this->returnValue($mongoCollection));

        return $mongoDb;
    }
}

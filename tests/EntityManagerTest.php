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

use IMT\ObjectMapper\Fixture\Post;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class EntityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $persistence;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->persistence = $this
            ->getMockBuilder('IMT\ObjectMapper\Persistence\MongoDb\Persistence')
            ->disableOriginalConstructor()
            ->getMock();
        $this->persistence
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('smth'));

        $metadata = include __DIR__ . '/metadata.smth.php';

        $this->entityManager = new EntityManager($this->persistence);
        $this->entityManager->registerHydrator(new TestHydrator($metadata, 'smth'));
        $this->entityManager->registerMetadata($metadata);
    }

    public function testGetRepositoryNotExists()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\RuntimeException',
            'The `stdClass` entity is not registered.'
        );

        $this->entityManager->getRepository('stdClass');
    }

    public function testGetRepositoryBase()
    {
        $this->assertInstanceOf(
            'IMT\ObjectMapper\EntityRepository',
            $this->entityManager->getRepository('IMT\ObjectMapper\Fixture\Smth')
        );
    }

    public function testGetRepository()
    {
        $class = 'IMT\ObjectMapper\Fixture\Post';
        $repository  = $this->entityManager->getRepository($class);

        $this->assertInstanceOf($class . 'Repository', $repository);
        $this->assertSame($repository, $this->entityManager->getRepository($class));
    }

    public function testFindByIdInvalidMetadata()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\RuntimeException',
            'The `stdClass` entity is not registered.'
        );

        $this->entityManager->findById('stdClass', '');
    }

    public function testFindByIdNotExists()
    {
        $this->assertNull(
            $this->entityManager->findById('IMT\ObjectMapper\Fixture\Post', 100500)
        );
    }

    public function testFindById()
    {
        $data = array(
            'id'       => 'id',
            'title'    => 'title',
            'comments' => array(
                array(
                    'id'      => 'id',
                    'content' => 'content',
                ),
            ),
        );

        $this
            ->persistence
            ->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($data['id']))
            ->will($this->returnValue($data));

        $class = 'IMT\ObjectMapper\Fixture\Post';

        $entity = $this->entityManager->findById($class, (string) $data['id']);

        $this->assertInstanceOf($class, $entity);
    }

    public function testFindByCriteriaInvalidMetadata()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\RuntimeException',
            'The `stdClass` entity is not registered.'
        );

        $this->entityManager->findByCriteria('stdClass', new Criteria());
    }

    public function testFindByCriteriaNotExists()
    {
        $this->persistence
            ->expects($this->once())
            ->method('findByCriteria')
            ->will($this->returnValue(new \ArrayIterator(array())));

        $entities = $this->entityManager
            ->findByCriteria('IMT\ObjectMapper\Fixture\Post', new Criteria());

        $this->assertInstanceOf('IMT\ObjectMapper\Persistence\ResultSet', $entities);
        $this->assertCount(0, $entities);
    }

    public function testFindByCriteria()
    {
        $data = array(
            array(
                'id'       => 'id',
                'title'    => 'title',
                'comments' => array(
                    array(
                        'id'      => 'id',
                        'content' => 'content',
                    ),
                ),
            ),
            array(
                'id'       => 'id',
                'title'    => 'title',
                'comments' => array(
                    array(
                        'id'      => 'id',
                        'content' => 'content',
                    ),
                ),
            ),
        );

        $this
            ->persistence
            ->expects($this->once())
            ->method('findByCriteria')
            ->will($this->returnValue(new \ArrayIterator($data)));

        $class = 'IMT\ObjectMapper\Fixture\Post';

        $criteria = new Criteria();
        $criteria->addClause('id', 0, Criteria::COMPARISON_GT);

        $entities = $this->entityManager->findByCriteria($class, $criteria);

        $this->assertInstanceOf('IMT\ObjectMapper\Persistence\ResultSet', $entities);
        $this->assertCount(2, $entities);

        $entities = iterator_to_array($entities);
        $this->assertInstanceOf($class, $entities[0]);
        $this->assertInstanceOf($class, $entities[1]);
    }

    public function testPersistInvalidEntity()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\InvalidArgumentException',
            'Expected argument of type object, string given.'
        );

        $this->entityManager->persist('stdClass');
    }

    public function testPersistFailed()
    {
        $this
            ->persistence
            ->expects($this->once())
            ->method('persist')
            ->will($this->returnValue(null));

        $this->assertFalse($this->entityManager->persist(new Post()));
    }

    public function testPersist()
    {
        $entity   = new Post();
        $entityId = '509a258b9b57f22373000000';

        $this
            ->persistence
            ->expects($this->once())
            ->method('persist')
            ->will($this->returnValue(array('id' => $entityId)));

        $this->assertTrue($this->entityManager->persist($entity));
        $this->assertEquals($entityId, $entity->getId());
    }

    public function testRemoveInvalidEntity()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\InvalidArgumentException',
            'Expected argument of type object, string given.'
        );

        $this->entityManager->remove('stdClass');
    }

    public function testRemove()
    {
        $entity = new Post('509a258b9b57f22373000000');

        $this
            ->persistence
            ->expects($this->once())
            ->method('remove');

        $this->entityManager->remove($entity);
    }
}

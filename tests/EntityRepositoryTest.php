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
class EntityRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this
            ->getMockBuilder('IMT\ObjectMapper\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityRepository = new EntityRepository($this->entityManager, 'stdClass');
    }

    public function testConstruct()
    {
        $this->assertAttributeInstanceOf(
            'IMT\ObjectMapper\EntityManager',
            'entityManager',
            $this->entityRepository
        );
        $this->assertAttributeEquals('stdClass', 'entityClass', $this->entityRepository);
    }

    public function testFindById()
    {
        $this->entityManager
            ->expects($this->once())
            ->method('findById')
            ->with($this->equalTo('stdClass'), $this->equalTo(100500))
            ->will($this->returnValue('smth'));

        $this->assertSame('smth', $this->entityRepository->findById(100500));
    }

    public function testFindOneByCriteria()
    {
        $criteria = new Criteria();

        $this->entityManager
            ->expects($this->once())
            ->method('findOneByCriteria')
            ->with($this->equalTo('stdClass'), $this->equalTo($criteria))
            ->will($this->returnValue('smth'));

        $this->assertSame('smth', $this->entityRepository->findOneByCriteria($criteria));
    }

    public function testFindByCriteria()
    {
        $criteria = new Criteria();

        $this->entityManager
            ->expects($this->once())
            ->method('findByCriteria')
            ->with($this->equalTo('stdClass'), $this->equalTo($criteria))
            ->will($this->returnValue('smth'));

        $this->assertSame('smth', $this->entityRepository->findByCriteria($criteria));
    }

    public function testFindByEmptyCriteria()
    {
        $criteria = new Criteria();

        $this->entityManager
            ->expects($this->once())
            ->method('findByCriteria')
            ->with($this->equalTo('stdClass'), $this->equalTo($criteria))
            ->will($this->returnValue('smth'));

        $this->assertSame('smth', $this->entityRepository->findByEmptyCriteria($criteria));
    }
}

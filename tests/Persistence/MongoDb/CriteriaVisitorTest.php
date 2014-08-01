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
class CriteriaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CriteriaVisitor
     */
    private $criteriaVisitor;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->criteriaVisitor = new CriteriaVisitor();
    }

    public function testVisitClauseNullComparison()
    {
        $criteria = new Criteria();
        $criteria->addClause('f', 'v');

        $this->assertEquals(
            array('f' => 'v'),
            $this->criteriaVisitor->visitClause($criteria)
        );
    }

    public function testVisitClauseInvalidComparison()
    {
        $this->setExpectedException(
            'IMT\ObjectMapper\Exception\LogicException',
            'The `smth` comparison is not supported.'
        );

        $criteria = new Criteria();
        $criteria->addClause('f', 'v', 'smth');

        $this->criteriaVisitor->visitClause($criteria);
    }

    public function testVisitClause()
    {
        $criteria = new Criteria();
        $criteria->addClause('f1', 'v1', Criteria::COMPARISON_GT);
        $criteria->addClause('f2', 'v2', Criteria::COMPARISON_GTE);
        $criteria->addClause('f3', 'v3', Criteria::COMPARISON_LT);
        $criteria->addClause('f4', 'v4', Criteria::COMPARISON_LTE);
        $criteria->addClause('f5', 'v5', Criteria::COMPARISON_NE);

        $this->assertEquals(
            array(
                'f1' => array('$gt' => 'v1'),
                'f2' => array('$gte' => 'v2'),
                'f3' => array('$lt' => 'v3'),
                'f4' => array('$lte' => 'v4'),
                'f5' => array('$ne' => 'v5'),
            ),
            $this->criteriaVisitor->visitClause($criteria)
        );
    }

    public function testVisitClauseNested()
    {
        $criteria = new Criteria();
        $criteria->addClause('f1', 'v1', Criteria::COMPARISON_GT);
        $criteria->addClause('f2', 'v2', Criteria::COMPARISON_GTE);

        $nestedCriteria = new Criteria();
        $nestedCriteria->addClause('f1', 'v1', Criteria::COMPARISON_LT);
        $nestedCriteria->addClause('f2', 'v2', Criteria::COMPARISON_LTE);
        $nestedCriteria->addClause('f3', clone $criteria);

        $criteria->addClause('f3', $nestedCriteria);

        $this->assertEquals(
            array(
                'f1'       => array('$gt' => 'v1'),
                'f2'       => array('$gte' => 'v2'),
                'f3.f1'    => array('$lt' => 'v1'),
                'f3.f2'    => array('$lte' => 'v2'),
                'f3.f3.f1' => array('$gt' => 'v1'),
                'f3.f3.f2' => array('$gte' => 'v2'),
            ),
            $this->criteriaVisitor->visitClause($criteria)
        );
    }

    public function testVisitClauseRegex()
    {
        $criteria = new Criteria();
        $criteria->addClause('f1', '/^1307@yandex\.ru$/i', Criteria::COMPARISON_REGEX);

        $query = $this->criteriaVisitor->visitClause($criteria);

        $this->assertArrayHasKey('f1', $query);
        $this->assertArrayHasKey('$regex', $query['f1']);
        $this->assertInstanceOf('MongoRegex', $query['f1']['$regex']);
    }

    public function testVisitClauseTypeSpecified()
    {
        $criteria = new Criteria();
        $criteria->addClause('f1', 'v1', null, 'id');

        $query = $this->criteriaVisitor->visitClause($criteria);

        $this->assertArrayHasKey('f1', $query);
        $this->assertInstanceOf('MongoId', $query['f1']);
    }

    public function testVisitClauseComparisonAndTypeSpecified()
    {
        $criteria = new Criteria();
        $criteria->addClause('f1', (new \DateTime())->getTimestamp(), Criteria::COMPARISON_LTE, 'date');

        $query = $this->criteriaVisitor->visitClause($criteria);

        $this->assertArrayHasKey('f1', $query);
        $this->assertInstanceOf('MongoDate', $query['f1']['$lte']);
    }
}

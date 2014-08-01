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
class CriteriaTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAndGetClause()
    {
        $criteria = new Criteria();
        $criteria->addClause('f1', 'v1', Criteria::COMPARISON_GT);
        $criteria->addClause('f2', 'v2', Criteria::COMPARISON_GTE);
        $criteria->addClause('f3', 'v3', Criteria::COMPARISON_LT, 'string');

        $this->assertEquals(
            array(
                'f1' => array(Criteria::COMPARISON_GT, 'v1', null),
                'f2' => array(Criteria::COMPARISON_GTE, 'v2', null),
                'f3' => array(Criteria::COMPARISON_LT, 'v3', 'string'),
            ),
            $criteria->getClauses()
        );
    }

    public function testAddAndGetSort()
    {
        $criteria = new Criteria();
        $criteria->addSort('f1', Criteria::SORT_ASC);
        $criteria->addSort('f2', Criteria::SORT_DESC);

        $this->assertEquals(
            array(
                'f1' => Criteria::SORT_ASC,
                'f2' => Criteria::SORT_DESC,
            ),
            $criteria->getSort()
        );
    }

    public function testSetAndGetLimit()
    {
        $criteria = new Criteria();
        $criteria->setLimit('100500');

        $this->assertSame(100500, $criteria->getLimit());
    }
}

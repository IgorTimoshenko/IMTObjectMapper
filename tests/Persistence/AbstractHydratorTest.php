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

use IMT\ObjectMapper\Fixture\Comment;
use IMT\ObjectMapper\Fixture\Post;
use IMT\ObjectMapper\TestHydrator;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class AbstractHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \IMT\ObjectMapper\Persistence\AbstractHydrator
     */
    private $hydrator;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->hydrator = new TestHydrator(include __DIR__ . '/../metadata.smth.php', 'smth');
    }

    public function testExtract()
    {
        $now  = new \DateTime();
        $post = new Post('id');
        $post->setCreatedAt($now);
        $post->setTitle('title');

        $comment = new Comment('id');
        $comment->setCreatedAt($now);
        $comment->setContent('content');

        $post->setComments(array($comment));

        $this->assertEquals(
            array(
                'id'        => 'id',
                'createdAt' => $now,
                'title'     => 'title',
                'comments'  => array(
                    array(
                        'id'        => 'id',
                        'createdAt' => $now,
                        'content'   => 'content',
                     ),
                ),
            ),
            $this->hydrator->extract($post)
        );
    }

    public function testHydrateFieldNull()
    {
        $data = array(
            'id'        => 'id',
            'createdAt' => null,
        );

        $class  = 'IMT\ObjectMapper\Fixture\Post';
        $object = $this->hydrator->hydrate($data, $class);

        $this->assertInstanceOf($class, $object);
        $this->assertNull($object->getCreatedAt());
    }

    public function testHydrate()
    {
        $now  = new \DateTime();
        $data = array(
            'id'        => 'id',
            'createdAt' => $now,
            'title'     => 'title',
            'comments'  => array(
                array(
                    'id'        => 'id',
                    'createdAt' => $now,
                    'content'   => 'content',
                )
            )
        );

        $class  = 'IMT\ObjectMapper\Fixture\Post';
        $object = $this->hydrator->hydrate($data, $class);

        $this->assertInstanceOf($class, $object);
        $this->assertEquals($data['id'], $object->getId());
        $this->assertEquals($data['createdAt'], $object->getCreatedAt());
        $this->assertEquals($data['title'], $object->getTitle());

        $comments = $object->getComments();
        $this->assertInternalType('array', $comments);
        $this->assertCount(1, $comments);
        $this->assertInstanceOf('IMT\ObjectMapper\Fixture\Comment', $comments[0]);
        $this->assertEquals($data['comments'][0]['id'], $comments[0]->getId());
        $this->assertEquals($data['comments'][0]['createdAt'], $comments[0]->getCreatedAt());
        $this->assertEquals($data['comments'][0]['content'], $comments[0]->getContent());
    }
}

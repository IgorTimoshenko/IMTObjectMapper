<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return array(
    'smth' => array(
        'IMT\ObjectMapper\Fixture\Post' => array(
            'collection' => 'c',
            'fields'     => array(
                'id'        => 'integer',
                'createdAt' => 'DateTime',
                'title'     => 'string',
                'comments'  => array(
                    'IMT\ObjectMapper\Fixture\Comment',
                    array(
                        'id'        => 'integer',
                        'createdAt' => 'DateTime',
                        'content'   => 'string',
                    )
                )
            ),
        ),
        'IMT\ObjectMapper\Fixture\Smth' => array(),
    ),
);

<?php

/*
 * This file is part of the IMTObjectMapper package.
 *
 * (c) Igor M. Timoshenko <igor.timoshenko@i.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IMT\ObjectMapper\Persistence\MongoDb\Type;

use IMT\ObjectMapper\Persistence\TypeInterface;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class Type implements TypeInterface
{
    /**
     * @var TypeInterface[]
     */
    private static $types = array();

    /**
     * @param  string        $type
     * @return TypeInterface
     */
    public static function getType($type)
    {
        if (!isset(self::$types[$type])) {
            $typeClass = __NAMESPACE__ . '\\' . ucfirst($type) . 'Type';

            if (!@class_exists($typeClass)) {
                self::$types[$type] = new self();
            } else {
                self::$types[$type] = new $typeClass;
            }
        }

        return self::$types[$type];
    }

    /**
     * {@inheritDoc}
     */
    public function toDatabase($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function toPhp($value)
    {
        return $value;
    }
}

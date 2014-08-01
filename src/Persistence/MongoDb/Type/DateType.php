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

use IMT\ObjectMapper\Exception\InvalidArgumentException;
use IMT\ObjectMapper\Persistence\TypeInterface;

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
class DateType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function toDatabase($value)
    {
        if ($value === null) {
            return null;
        } elseif ($value instanceof \DateTime) {
            $timestamp = $value->getTimestamp();
        } elseif (is_numeric($value)) {
            $timestamp = $value;
        } elseif (is_string($value)) {
            $timestamp = strtotime($value);
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Could not convert %s to a date value',
                    is_scalar($value) ? '"' . $value . '"' : gettype($value)
                )
            );
        }

        return new \MongoDate($timestamp);
    }

    /**
     * {@inheritDoc}
     */
    public function toPhp($value)
    {
        if ($value === null) {
            return null;
        } elseif ($value instanceof \MongoDate) {
            $timestamp = $value->sec;
        } elseif (is_numeric($value)) {
            $timestamp = $value;
        } elseif (is_string($value)) {
            $timestamp = strtotime($value);
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Could not convert %s to a date value',
                    is_scalar($value) ? '"' . $value . '"' : gettype($value)
                )
            );
        }

        return (new \DateTime())->setTimestamp($timestamp);
    }
}

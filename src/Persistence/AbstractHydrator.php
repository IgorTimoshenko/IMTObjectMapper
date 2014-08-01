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

/**
 * @author Igor Timoshenko <i.timoshenko@i.ua>
 */
abstract class AbstractHydrator
{
    /**
     * @var array
     */
    protected $metadata;

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param  string        $type
     * @return TypeInterface
     */
    abstract public function getType($type);

    /**
     * @param array $metadata
     */
    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param  object $object
     * @param  array  $fields
     * @return array
     */
    public function extract($object, array $fields = array())
    {
        $objectReflection = new \ReflectionObject($object);

        if (!$fields) {
            $fields = $this->metadata[$this->getName()][get_class($object)]['fields'];
        }

        $data = array();

        foreach ($fields as $field => $options) {
            if (!$objectReflection->hasProperty($field)) {
                continue;
            }

            $value = $object->{'get' . ucfirst($field)}();

            if (is_string($options)) {
                $value = $this->getType($options)->toDatabase($value);
            } elseif (is_array($options)) {
                list($nClass, $nFields) = $options;

                $values = array();

                foreach ($value as $v) {
                    $values[] = $this->extract($v, $nFields);
                }

                $value = $values;
            }

            $data[$field] = $value;
        }

        return $data;
    }

    /**
     * @param  array  $data
     * @param  string $class
     * @param  array  $fields
     * @return object
     */
    public function hydrate(array $data, $class, array $fields = array())
    {
        $object = new $class();
        $objectReflection = new \ReflectionObject($object);

        if (!$fields) {
            $fields = $this->metadata[$this->getName()][$class]['fields'];
        }

        foreach ($fields as $field => $options) {
            if (!$objectReflection->hasProperty($field)) {
                continue;
            }

            if (!array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];

            if (is_string($options)) {
                $value = $this->getType($options)->toPhp($value);
            } elseif (is_array($options)) {
                list($nClass, $nFields) = $options;

                $values = array();

                foreach ($value as $v) {
                    $values[] = $this->hydrate($v, $nClass, $nFields);
                }

                $value = $values;
            }

            $methodName = 'set' . ucfirst($field);

            if (!$objectReflection->hasMethod($methodName)) {
                $propertyReflection = $objectReflection->getProperty($field);
                $propertyReflection->setAccessible(true);
                $propertyReflection->setValue($object, $value);
            } else {
                $object->$methodName($value);
            }
        }

        return $object;
    }
}

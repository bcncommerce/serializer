<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Bcn\Component\Serializer\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    /** @var SerializerInterface[] */
    protected $properties = array();

    /** @var string|callback */
    protected $dataClass;

    /** @var PropertyAccessorInterface */
    protected $accessor;

    /**
     * @param string|callback $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param  string              $name
     * @param  SerializerInterface $serializer
     * @return $this
     */
    public function add($name, SerializerInterface $serializer)
    {
        $this->properties[$name] = $serializer;

        return $this;
    }

    /**
     * @param $object
     * @return array
     */
    public function serialize($object)
    {
        if ($object === null) {
            return $object;
        }

        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf('Expected argument is object, %s given', gettype($object)));
        }

        $data = array();
        foreach ($this->properties as $name => $serializer) {
            $data[$name] = $serializer->serialize($this->getProperty($object, $name));
        }

        return $data;
    }

    /**
     * @param $data
     * @param  null       $object
     * @return array|null
     */
    public function unserialize($data, &$object = null)
    {
        $object = $object ?: $this->newInstance();
        foreach ($this->properties as $name => $serializer) {
            if ($value = isset($data[$name]) ? $data[$name] : null) {
                $value = $serializer->unserialize($value);
            }
            $this->setProperty($object, $name, $value);
        }

        return $object;
    }

    /**
     * @return array
     */
    public function newInstance()
    {
        if (is_callable($this->dataClass)) {
            return call_user_func($this->dataClass);
        }

        return new $this->dataClass();
    }

    /**
     * @param  object|array $object
     * @param  string       $property
     * @return mixed
     */
    protected function getProperty($object, $property)
    {
        return $this->accessor->getValue($object, $property);
    }

    /**
     * @param $object
     * @param $property
     * @param $value
     */
    protected function setProperty($object, $property, $value)
    {
        $this->accessor->setValue($object, $property, $value);
    }
}

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
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

class Normalizer implements NormalizerInterface
{
    /** @var NormalizerInterface[] */
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
     * @param  NormalizerInterface $normalizer
     * @return $this
     */
    public function add($name, NormalizerInterface $normalizer)
    {
        $this->properties[$name] = $normalizer;

        return $this;
    }

    /**
     * @param $object
     * @return array
     */
    public function normalize($object)
    {
        if ($object === null) {
            return $object;
        }

        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf('Expected argument is object, %s given', gettype($object)));
        }

        $data = array();
        foreach ($this->properties as $name => $normalizer) {
            $data[$name] = $normalizer->normalize($this->getProperty($object, $name));
        }

        return $data;
    }

    /**
     * @param $data
     * @param  null       $object
     * @return array|null
     */
    public function denormalize($data, &$object = null)
    {
        $object = $object ?: $this->newInstance();
        foreach ($this->properties as $name => $normalizer) {
            if ($value = isset($data[$name]) ? $data[$name] : null) {
                $value = $normalizer->denormalize($value);
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

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

    /** @var array */
    protected $aliases = array();

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
     * @throws \Exception
     * @return $this
     */
    public function add($name, NormalizerInterface $normalizer)
    {
        if ($this->has($name)) {
            throw new \Exception(sprintf("Property %s already registered", $name));
        }

        $this->properties[$name] = $normalizer;

        return $this;
    }

    /**
     * Add property mapping with alias
     *
     * @param  string              $name
     * @param  string              $alias
     * @param  NormalizerInterface $normalizer
     * @throws \Exception
     * @return $this
     */
    public function alias($name, $alias, NormalizerInterface $normalizer)
    {
        $this->add($name, $normalizer);

        $this->aliases[$name] = $alias;

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function remove($name)
    {
        unset($this->properties[$name]);
        unset($this->aliases[$name]);

        return $this;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * @param  PropertyAccessorInterface $accessor
     * @return $this
     */
    public function setAccessor(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;

        return $this;
    }

    /**
     * @param  mixed $object
     * @return mixed
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
            $alias = isset($this->aliases[$name]) ? $this->aliases[$name] : $name;
            $data[$alias] = $normalizer->normalize($this->getProperty($object, $name));
        }

        return $data;
    }

    /**
     * @param  mixed $data
     * @param  mixed $object
     * @return mixed
     */
    public function denormalize($data, &$object = null)
    {
        $object = $object ?: $this->newInstance();

        foreach ($this->properties as $name => $normalizer) {
            $alias = isset($this->aliases[$name]) ? $this->aliases[$name] : $name;

            $value = isset($data[$alias]) ?
                $normalizer->denormalize($data[$alias]) :
                null;

            $this->setProperty($object, $name, $value);
        }

        return $object;
    }

    /**
     * @return object
     */
    protected function newInstance()
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
     * @param object|array $object
     * @param string       $property
     * @param mixed        $value
     */
    protected function setProperty($object, $property, $value)
    {
        $this->accessor->setValue($object, $property, $value);
    }
}

<?php
/**
 * This file is part of the prism project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Bcn\Component\Serializer\Definition\Accessor;
use Bcn\Component\Serializer\Definition\TransformerInterface;

class Definition
{
    /** @var string */
    protected $nodeName;

    /** @var Definition */
    protected $prototype;

    /** @var Definition[] */
    protected $properties = array();

    /** @var string|null */
    protected $propertyPath = null;

    /** @var PropertyAccessorInterface */
    protected $propertyAccessor;

    /** @var TransformerInterface */
    protected $transformer;

    /** @var callback|null */
    protected $factory;

    /**
     * @return bool
     */
    public function isScalar()
    {
        return !$this->isObject() && !$this->isArray();
    }

    /**
     * @return bool
     */
    public function isObject()
    {
        return count($this->properties) >= 1;
    }

    /**
     * @return bool
     */
    public function isArray()
    {
        return $this->prototype !== null && !$this->isObject();
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function setNodeName($name)
    {
        $this->nodeName = $name;

        return $this;
    }

    /**
     * @param  string     $name
     * @param  Definition $definition
     * @return $this
     * @throws \Exception
     */
    public function addProperty($name, Definition $definition)
    {
        if ($this->hasProperty($name)) {
            throw new \Exception(sprintf('Property "%s" already registered', $name));
        }

        $this->properties[$name] = $definition;

        return $this;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * @param  string     $name
     * @return Definition
     * @throws \Exception
     */
    public function getProperty($name)
    {
        if (!$this->hasProperty($name)) {
            throw new \Exception(sprintf('Property "%s" is not defined', $name));
        }

        return $this->properties[$name];
    }

    /**
     * @return Definition[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param  Definition $prototype
     * @return $this
     */
    public function setPrototype(Definition $prototype)
    {
        $this->prototype = $prototype;

        return $this;
    }

    /**
     * @return Definition
     */
    public function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * @param  null|string $propertyPath
     * @return $this
     */
    public function setPropertyPath($propertyPath)
    {
        $this->propertyPath = $propertyPath;

        return $this;
    }

    /**
     * @param  PropertyAccessorInterface $propertyAccessor
     * @return $this
     */
    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;

        return $this;
    }

    /**
     * @param  TransformerInterface $transformer
     * @return $this
     */
    public function setTransformer(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * @param  callback $factory
     * @return $this
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function &extract(&$data)
    {
        $value = &$this->getAccessor($data)->get();
        $value = $this->transformer ? $this->transformer->normalize($value) : $value;

        return $value;
    }
    /**
     * @param  mixed $object
     * @param  mixed $data
     * @return mixed
     */
    public function settle(&$object, $data)
    {
        $data = $this->transformer ? $this->transformer->denormalize($data) : $data;
        $this->getAccessor($object)->set($data);

        return $object;
    }

    /**
     * @return mixed
     */
    public function create()
    {
        if ($factory = $this->factory) {
            return $factory();
        }

        return $this->isArray() ? array() : null;
    }

    /**
     * @param  mixed    $origin
     * @return Accessor
     */
    protected function getAccessor(&$origin)
    {
        return new Accessor($origin, $this->propertyPath, $this->propertyAccessor);
    }
}

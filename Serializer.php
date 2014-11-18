<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Bcn\Component\Serializer\Decoder\DecoderInterface;
use Bcn\Component\Serializer\Encoder\EncoderInterface;
use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Serializer implements SerializerInterface
{
    /** @var SerializerInterface[] */
    protected $properties = array();

    /** @var array */
    protected $names = array();

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
     * @param  string              $property
     * @throws \Exception
     * @return $this
     */
    public function add($name, SerializerInterface $serializer, $property = null)
    {
        if ($this->has($name)) {
            throw new \Exception(sprintf("Property %s already registered", $name));
        }

        $this->properties[$name] = $serializer;
        $this->names[$name]      = $property ?: $name;

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function remove($name)
    {
        unset($this->properties[$name]);
        unset($this->names[$name]);

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
     * @param  mixed            $data
     * @param  EncoderInterface $encoder
     * @return EncoderInterface
     */
    public function serialize($data, EncoderInterface $encoder)
    {
        if ($data === null) {
            $encoder->write(null);

            return $encoder;
        }

        foreach ($this->properties as $property => $serializer) {
            $encoder->node($property, $serializer->getNodeType());
            $serializer->serialize($this->getProperty($data, $this->names[$property]), $encoder);
            $encoder->end();
        }

        return $encoder;
    }

    /**
     * @param  DecoderInterface $decoder
     * @param  object|null      $object
     * @return object
     */
    public function unserialize(DecoderInterface $decoder, &$object = null)
    {
        if ($object === null) {
            $object = $this->newInstance();
        }

        foreach ($this->properties as $property => $serializer) {
            if ($decoder->exists($property)) {
                $decoder->node($property, $serializer->getNodeType());
                $value = $serializer->unserialize($decoder);
                $decoder->end();
            } else {
                $value = null;
            }

            $this->setProperty($object, $this->names[$property], $value);
        }

        return $object;
    }

    /**
     * @return string
     */
    public function getNodeType()
    {
        return 'object';
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

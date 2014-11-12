<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeFactory
{
    /** @var TypeInterface[] */
    protected $types = array();

    /**
     * @param  TypeInterface|string $type
     * @param  array                $options
     * @return NormalizerInterface
     */
    public function create($type, array $options = array())
    {
        $optionsResolver = new OptionsResolver();

        $resolved = $this->resolveType($type);
        $resolved->setDefaultOptions($optionsResolver);

        return $resolved->getNormalizer($this, $optionsResolver->resolve($options));
    }

    /**
     * @param  TypeInterface $type
     * @return $this
     * @throws \Exception
     */
    public function addType(TypeInterface $type)
    {
        $name = $type->getName();
        if ($this->hasType($name)) {
            throw new \Exception("Type \"$name\" already defined");
        }

        $this->types[$name] = $type;

        return $this;
    }

    /**
     * @param  string        $type
     * @return TypeInterface
     * @throws \Exception
     */
    public function getType($type)
    {
        $name = $this->resolveTypeName($type);

        if (!isset($this->types[$name])) {
            throw new \Exception("Type \"$type\" is not defined");
        }

        return $this->types[$name];
    }

    /**
     * @param  TypeInterface|string $type
     * @return bool
     */
    public function hasType($type)
    {
        return isset($this->types[$this->resolveTypeName($type)]);
    }

    /**
     * @param $type
     * @return string
     */
    protected function resolveTypeName($type)
    {
        return $type instanceof TypeInterface ? $type->getName() : $type;
    }

    /**
     * @param $type
     * @throws \InvalidArgumentException
     * @return TypeInterface
     */
    protected function resolveType($type)
    {
        if ($type instanceof TypeInterface) {
            return $type;
        }

        if ($this->hasType($type)) {
            return $this->getType($type);
        }

        throw new \InvalidArgumentException("Type \"$type\" is not registered");
    }
}

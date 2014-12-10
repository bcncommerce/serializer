<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Bcn\Component\Serializer\Definition\Builder;
use Bcn\Component\Serializer\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Resolver
{
    /** @var TypeInterface[] */
    protected $types = array();

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
     * @param  TypeInterface|string $type
     * @return bool
     */
    public function hasType($type)
    {
        return isset($this->types[$this->resolveTypeName($type)]);
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
     * @return string
     */
    protected function resolveTypeName($type)
    {
        return $type instanceof TypeInterface ? $type->getName() : $type;
    }

    /**
     * @param  string     $type
     * @param  array      $options
     * @return Definition
     */
    public function getDefinition($type, array $options = array())
    {
        $type = $this->resolveType($type);

        $definition = new Definition();

        $resolver = new OptionsResolver();
        $type->setDefaultOptions($resolver);

        $builder = new Builder($this, $definition);
        $type->build($builder, $resolver->resolve($options));

        return $definition;
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

        return $this->getType($type);
    }
}

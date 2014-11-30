<?php
/**
 * This file is part of the prism project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition;

use Bcn\Component\Serializer\Resolver;
use Bcn\Component\Serializer\Definition;

class Builder
{
    /** @var Resolver */
    protected $resolver;

    /** @var Definition */
    protected $definition;

    /** @var bool */
    protected $required = false;

    /** @var mixed */
    protected $defaultValue = null;

    /** @var Builder */
    protected $parent;

    /**
     * @param Resolver   $resolver
     * @param Definition $definition
     * @param Builder    $parent
     */
    public function __construct(Resolver $resolver, Definition $definition, Builder $parent = null)
    {
        $this->resolver   = $resolver;
        $this->definition = $definition;
        $this->parent     = $parent;
    }

    /**
     * @param  string            $name
     * @param  string|Definition $type
     * @param  array             $options
     * @return $this
     * @throws \Exception
     */
    public function node($name, $type = null, array $options = array())
    {
        $definition = $this->resolve($type, $options);
        $definition->setNodeName($name);

        $this->definition->addProperty($name, $definition);

        return new Builder($this->resolver, $definition, $this);
    }

    /**
     * @param  string|Definition $type
     * @param  array             $options
     * @return Builder
     */
    public function prototype($type = null, array $options = array())
    {
        $definition = $this->resolve($type, $options);

        $this->definition->setPrototype($definition);

        return new Builder($this->resolver, $definition, $this);
    }

    /**
     * @param  string $propertyPath
     * @return $this
     */
    public function keys($propertyPath)
    {
        $this->definition->setKeyProperty($propertyPath);

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function name($name)
    {
        $this->definition->setNodeName($name);

        return $this;
    }

    /**
     * @return Builder
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * @param  TransformerInterface $transformer
     * @return $this
     */
    public function transform(TransformerInterface $transformer)
    {
        $this->definition->setTransformer($transformer);

        return $this;
    }

    /**
     * @param  callback $factory
     * @return $this
     */
    public function factory($factory)
    {
        $this->definition->setFactory($factory);

        return $this;
    }

    /**
     * @param  string $property
     * @return $this
     */
    public function property($property)
    {
        $this->definition->setPropertyPath($property);

        return $this;
    }

    /**
     * @param $type
     * @param  array      $options
     * @return Definition
     */
    protected function resolve($type, array $options)
    {
        if ($type instanceof Definition) {
            $definition = $type;
        } elseif ($type === null) {
            $definition = new Definition();
        } else {
            $definition = $this->resolver->getDefinition($type, $options);
        }

        return $definition;
    }
}

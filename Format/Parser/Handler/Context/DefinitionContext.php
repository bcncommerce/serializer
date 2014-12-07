<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler\Context;

use Bcn\Component\Serializer\Definition;

class DefinitionContext implements ContextInterface
{
    /** @var string */
    protected $content;

    /** @var boolean */
    protected $initialized;

    /** @var mixed */
    protected $origin;

    /** @var Definition */
    protected $definition;

    /** @var int */
    protected $index;

    /** @var int|string */
    protected $key;

    /** @var array */
    protected $collection;

    /**
     * @param mixed      $origin
     * @param Definition $definition
     */
    public function __construct(&$origin, Definition $definition)
    {
        $this->origin     = &$origin;
        $this->definition = $definition;
    }

    /**
     * @param  string           $name
     * @param  array            $attributes
     * @return ContextInterface
     * @throws \Exception
     */
    public function start($name, array $attributes = array())
    {
        $this->initialize();

        if ($this->definition->isArray()) {
            $prototype = $this->definition->getPrototype();

            $keyName = $this->definition->getKeyName();
            if ($keyName && isset($attributes[$keyName])) {
                $this->key = $attributes[$keyName];
            } else {
                $this->key = $this->index;
                $this->index++;
            }

            if (!isset($this->collection[$this->key])) {
                $this->collection[$this->key] = $prototype->create();
            }

            $this->definition->settleKey($this->collection[$this->key], $this->key);

            return new DefinitionContext($this->collection[$this->key], $prototype);
        }

        if ($this->definition->isObject() && $this->definition->hasProperty($name)) {
            $object = &$this->definition->extract($this->origin);

            return new DefinitionContext($object, $this->definition->getProperty($name));
        }

        return new NullContext();
    }

    /**
     * @param string $data
     */
    public function append($data)
    {
        $this->initialize();

        $this->content .= $data;
    }

    /**
     * Close sub context
     *
     * @param string $name
     * @param mixed  $value
     */
    public function end($name, $value)
    {
        if ($this->definition->isArray()) {

        }

        if ($this->definition->isObject() && $this->definition->hasProperty($name)) {
            $object = &$this->definition->extract($this->origin);
            $property = $this->definition->getProperty($name);
            $property->settle($object, $value);
        }
    }

    /**
     * Reset context state
     */
    public function reset()
    {
        $this->content     = null;
        $this->initialized = false;
        $this->index       = 0;
    }

    /**
     * Fetch context content
     *
     * @return mixed
     */
    public function fetch()
    {
        if ($this->definition->isScalar()) {
            $this->definition->settle($this->origin, $this->content);
        }

        if ($this->definition->isArray()) {
            $this->definition->settle($this->origin, $this->collection);
        }

        return $this->definition->extract($this->origin);
    }

    /**
     *
     */
    protected function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        $value = $this->definition->extract($this->origin);

        if ($value === null) {
            $this->definition->settle($this->origin, $this->definition->create());
        }

        if ($this->definition->isArray()) {
            $this->collection = &$this->definition->extract($this->origin);
        }
    }
}

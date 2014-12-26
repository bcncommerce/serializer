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
    /** @var boolean */
    protected $initialized;

    /** @var mixed */
    protected $origin;

    /** @var Definition */
    protected $definition;

    /** @var string */
    protected $content;

    /** @var array */
    protected $collection;

    /** @var int */
    protected $index;

    /** @var int|string */
    protected $key;

    /** @var mixed */
    protected $object;

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
                $this->collection[$this->key] = $prototype->create($this->origin);
            }

            $this->definition->settleKey($this->collection[$this->key], $this->key);

            return new DefinitionContext($this->collection[$this->key], $prototype);
        }

        if ($this->definition->isObject() && $this->definition->hasProperty($name)) {
            return new DefinitionContext($this->object, $this->definition->getProperty($name));
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
    }

    /**
     * Reset context state
     */
    public function reset()
    {
        $this->content     = null;
        $this->initialized = false;
        $this->index       = 0;
        $this->key         = null;
        $this->collection  = array();
        $this->object      = null;
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

            return $this->content;
        }

        if ($this->definition->isArray()) {
            $this->definition->settle($this->origin, $this->collection);

            return $this->collection;
        }

        if ($this->definition->isObject()) {
            $this->definition->settle($this->origin, $this->object);

            return $this->object;
        }

        return null;
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

        if ($this->definition->isArray()) {
            $this->collection = &$this->definition->extract($this->origin);
        }

        if ($this->definition->isObject()) {
            $this->object = &$this->definition->extract($this->origin);

            if ($this->object === null) {
                $this->object = $this->definition->create($this->origin);
            }
        }
    }
}

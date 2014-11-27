<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler\Context;

use Bcn\Component\Serializer\Definition;

class Context implements ContextInterface
{
    /** @var string */
    protected $content;

    /** @var int */
    protected $index;

    /** @var boolean */
    protected $initialized;

    /** @var mixed */
    protected $origin;

    /** @var Definition */
    protected $definition;

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
            $collection = $this->definition->extract($this->origin);
            if (!isset($collection[$this->index])) {
                $collection[$this->index] = $prototype->create();
            }

            return new Context($collection[$this->index], $prototype);
        }

        if ($this->definition->isObject() && $this->definition->hasProperty($name)) {
            $object = $this->definition->extract($this->origin);

            return new Context($object, $this->definition->getProperty($name));
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
            $collection = $this->definition->extract($this->origin);
            $collection[$this->index] = $value;
            $this->definition->settle($this->origin, $collection);
            $this->index++;
        }

        if ($this->definition->isObject() && $this->definition->hasProperty($name)) {
            $object = $this->definition->extract($this->origin);
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
        $this->index       = 0;
        $this->initialized = false;
    }

    /**
     * Fetch context content
     *
     * @return mixed
     */
    public function fetch()
    {
        if ($this->definition->isScalar()) {
            return $this->content;
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
    }
}

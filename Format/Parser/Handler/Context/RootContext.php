<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler\Context;

use Bcn\Component\Serializer\Definition;

class RootContext extends Context
{
    /** @var mixed */
    protected $origin;

    /** @var Definition */
    protected $definition;

    /**
     * @param  string           $name
     * @param  array            $attributes
     * @return ContextInterface
     * @throws \Exception
     */
    public function start($name, array $attributes = array())
    {
        if ($this->definition->getNodeName() != $name) {
            // something is wrong here...
        }

        return new Context($this->origin, $this->definition);
    }

    /**
     * @param string $data
     */
    public function append($data)
    {
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
    }

    /**
     * Fetch context content
     *
     * @return mixed
     */
    public function fetch()
    {
        return $this->origin;
    }
}

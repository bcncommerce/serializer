<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler\Context;

class ArrayContext implements ContextInterface
{
    /** @var array|string */
    protected $data;

    /**
     * @param  string           $name
     * @param  array            $attributes
     * @return ContextInterface
     * @throws \Exception
     */
    public function start($name, array $attributes = array())
    {
        if (!is_array($this->data)) {
            $this->data = array();
        }

        return new self();
    }

    /**
     * @param string $data
     */
    public function append($data)
    {
        if (!is_array($this->data)) {
            $this->data .= $data;
        }
    }

    /**
     * Close sub context
     *
     * @param string $name
     * @param mixed  $value
     */
    public function end($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Reset context state
     */
    public function reset()
    {
        $this->data = null;
    }

    /**
     * Fetch context content
     *
     * @return mixed
     */
    public function fetch()
    {
        return $this->data;
    }
}

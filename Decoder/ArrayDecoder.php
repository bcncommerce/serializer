<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Decoder;

class ArrayDecoder implements DecoderInterface
{
    /** @var array */
    protected $data;

    /** @var mixed */
    protected $current;

    /** @var array */
    protected $context = array();

    /** @var array */
    protected $types = array();

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data      = array($data);
        $this->current   = &$this->data;
        $this->context[] = &$this->data;
        $this->types[]   = 'array';
    }

    /**
     * Enter a node
     *
     * @param  string|null $name
     * @param  string|null $type
     * @return bool
     */
    public function node($name = null, $type = null)
    {
        if (end($this->types) == 'array') {
            $name = null;
        }

        if ($name === null && is_array($this->current)) {
            $name =  key($this->current);
        }

        if ($name === null || !isset($this->current[$name])) {
            return false;
        }

        $this->types[]   = $type;
        $this->context[] = &$this->current;
        $this->current   = &$this->current[$name];

        if ($type == 'array') {
            reset($this->current);
        }

        return true;
    }

    /**
     * Get an iterator through the child nodes
     */
    public function next()
    {
        if (!next($this->current)) {
            $this->current = null;
        }
    }

    /**
     * Read a scalar idue
     *
     * @return string|boolean|null|integer|float
     */
    public function read()
    {
        return $this->current;
    }

    /**
     * Leave a node
     *
     * @return DecoderInterface
     */
    public function end()
    {
        $this->current = $this->context[count($this->context) - 1];
        array_pop($this->context);
        array_pop($this->types);

        return $this;
    }
}

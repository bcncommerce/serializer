<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

class ArrayEncoder implements EncoderInterface
{
    /** @var array */
    protected $context = array();

    /** @var array */
    protected $data = array();

    /** @var mixed */
    protected $current;

    /** @var array */
    protected $types = array();

    /**
     *
     */
    public function __construct()
    {
        $this->data      = array();
        $this->current   = &$this->data;
        $this->context[] = &$this->data;
        $this->types[]   = 'mixed';
    }

    /**
     * @param  string|null      $name
     * @param  string|null      $type
     * @return EncoderInterface
     */
    public function node($name = null, $type = null)
    {
        if (end($this->types) == 'array') {
            $name = null;
        }

        $item = $type == 'scalar' ? null : array();

        $this->context[] = &$this->current;
        $this->types[]   = $type;

        if ($name === null) {
            $this->current[] = &$item;
        } else {
            $this->current[$name] = &$item;
        }

        $this->current = &$item;

        return $this;
    }

    /**
     * @param  string           $value
     * @return EncoderInterface
     */
    public function write($value)
    {
        $this->current = $value;

        return $this;
    }

    /**
     * @return EncoderInterface
     */
    public function end()
    {
        $this->current = &$this->context[count($this->context) - 1];
        array_pop($this->context);
        array_pop($this->types);

        return $this;
    }

    /**
     * @return array
     */
    public function dump()
    {
        return is_array($this->data) ? current($this->data) : $this->data;
    }
}

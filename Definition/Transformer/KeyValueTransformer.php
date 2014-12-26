<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition\Transformer;

use Bcn\Component\Serializer\Definition\TransformerInterface;

class KeyValueTransformer implements TransformerInterface
{
    /** @var string */
    protected $keys;

    /** @var string */
    protected $values;

    /**
     * @param string $keys
     * @param string $values
     */
    public function __construct($keys = 'key', $values = 'value')
    {
        $this->keys = $keys;
        $this->values = $values;
    }

    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function normalize($value, $origin)
    {
        $pairs = array();
        foreach ($value as $key => $entry) {
            $pairs[] = array($this->keys => $key, $this->values => $entry);
        }

        return $pairs;
    }

    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function denormalize($value, $origin)
    {
        $items = array();
        foreach ($value as $entry) {
            $key  = isset($entry[$this->keys])   ? $entry[$this->keys]   : null;
            $item = isset($entry[$this->values]) ? $entry[$this->values] : null;

            if ($key === null) {
                $items[] = $item;
            } else {
                $items[$key] = $item;
            }
        }

        return $items;
    }
}

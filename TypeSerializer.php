<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

class TypeSerializer
{
    /** @var Serializer */
    private $serializer;

    /** @var string */
    private $type;

    /** @var array */
    private $options = array();

    /** @var string */
    private $format;

    /**
     * @param Serializer $serializer
     * @param string     $format
     * @param string     $type
     * @param array      $options
     */
    public function __construct(Serializer $serializer, $type, array $options = array(), $format = null)
    {
        $this->serializer = $serializer;
        $this->type       = $type;
        $this->options    = $options;
        $this->format     = $format;
    }

    /**
     * @param  mixed         $object
     * @param  resource|bool $stream
     * @param  string        $format
     * @return mixed
     */
    public function serialize($object, $stream, $format = null)
    {
        return $this->serializer->serialize($object, $this->type, $stream, $format ?: $this->format, $this->options);
    }

    /**
     * @param  resource|string $stream
     * @param  mixed           $object
     * @param  string          $format
     * @return mixed
     */
    public function unserialize($stream, &$object = null, $format = null)
    {
        return $this->serializer->unserialize($stream, $format ?: $this->format, $this->type, $this->options, $object);
    }
}

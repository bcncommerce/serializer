<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Serializer;

use Bcn\Component\Serializer\Decoder\DecoderInterface;
use Bcn\Component\Serializer\Encoder\EncoderInterface;

class RootSerializer implements SerializerInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var string */
    protected $nodeName;

    /**
     * @param string|null         $nodeName
     * @param SerializerInterface $serializer
     */
    public function __construct($nodeName = null, SerializerInterface $serializer)
    {
        $this->nodeName   = $nodeName;
        $this->serializer = $serializer;
    }

    /**
     * @param  mixed            $data
     * @param  EncoderInterface $encoder
     * @return EncoderInterface
     */
    public function serialize($data, EncoderInterface $encoder)
    {
        $encoder->node($this->nodeName, $this->serializer->getNodeType());
        $this->serializer->serialize($data, $encoder);
        $encoder->end();

        return $encoder;
    }

    /**
     * Unserialize data from $decoder to $object
     *
     * @param  DecoderInterface $decoder
     * @param  object|null      $object
     * @return object
     */
    public function unserialize(DecoderInterface $decoder, &$object = null)
    {
        if ($decoder->node($this->nodeName, $this->serializer->getNodeType())) {
            $object = $this->serializer->unserialize($decoder, $object);
            $decoder->end();
        } else {
            $object = null;
        }

        return $object;
    }

    /**
     * @return string
     */
    public function getNodeType()
    {
        return 'object';
    }
}

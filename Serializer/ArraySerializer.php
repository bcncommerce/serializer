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

class ArraySerializer implements SerializerInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var string */
    protected $itemNodeName;

    /**
     * @param SerializerInterface $serializer
     * @param string|null         $itemNodeName
     */
    public function __construct(SerializerInterface $serializer, $itemNodeName = null)
    {
        $this->serializer = $serializer;
        $this->itemNodeName = $itemNodeName;
    }

    /**
     * @param  mixed            $data
     * @param  EncoderInterface $encoder
     * @return EncoderInterface
     */
    public function serialize($data, EncoderInterface $encoder)
    {
        foreach ($data as $row) {
            $encoder->node($this->itemNodeName, $this->serializer->getNodeType());
            $this->serializer->serialize($row, $encoder);
            $encoder->end();
        }

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
        if ($object === null) {
            $object = array();
        }

        while ($decoder->node($this->itemNodeName, $this->serializer->getNodeType())) {
            $object[] = $this->serializer->unserialize($decoder);
            $decoder->end()->next();
        }

        return $object;
    }

    /**
     * @return string
     */
    public function getNodeType()
    {
        return 'array';
    }

    /**
     * @return SerializerInterface
     */
    public function getItemSerializer()
    {
        return $this->serializer;
    }
}

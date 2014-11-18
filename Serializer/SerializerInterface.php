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

interface SerializerInterface
{
    /**
     * Serialize $data using $encoder
     *
     * @param  mixed            $data
     * @param  EncoderInterface $encoder
     * @return EncoderInterface
     */
    public function serialize($data, EncoderInterface $encoder);

    /**
     * Unserialize data from $decoder to $object
     *
     * @param  DecoderInterface $decoder
     * @param  object|null      $object
     * @return object
     */
    public function unserialize(DecoderInterface $decoder, &$object = null);

    /**
     * Basic node type - array, object or scalar
     *
     * @return string
     */
    public function getNodeType();
}

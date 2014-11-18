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

class ScalarSerializer implements SerializerInterface
{
    /** @var callable */
    protected $normalizer = null;

    /** @var callable */
    protected $denormalizer = null;

    /**
     * @param  callable $normalizer
     * @return $this
     */
    public function setNormalizer($normalizer)
    {
        if (!is_callable($normalizer)) {
            throw new \InvalidArgumentException(
                sprintf("Normalizer should be callable, %s given", gettype($normalizer))
            );
        }

        $this->normalizer = $normalizer;

        return $this;
    }

    /**
     * @return callable
     */
    public function getNormalizer()
    {
        return $this->normalizer;
    }

    /**
     * @param  callable $denormalizer
     * @return $this
     */
    public function setDenormalizer($denormalizer)
    {
        if (!is_callable($denormalizer)) {
            throw new \InvalidArgumentException(
                sprintf("Normalizer should be callable, %s given", gettype($denormalizer))
            );
        }

        $this->denormalizer = $denormalizer;

        return $this;
    }

    /**
     * @return callable
     */
    public function getDenormalizer()
    {
        return $this->denormalizer;
    }

    /**
     * @param  mixed            $data
     * @param  EncoderInterface $encoder
     * @return EncoderInterface
     */
    public function serialize($data, EncoderInterface $encoder)
    {
        if ($this->normalizer) {
            $data = call_user_func($this->normalizer, $data);
        }

        $encoder->write($data);

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
        $object = $decoder->read();

        if ($this->denormalizer) {
            $object = call_user_func($this->denormalizer, $object);
        }

        return $object;
    }

    /**
     * @return string
     */
    public function getNodeType()
    {
        return 'scalar';
    }
}

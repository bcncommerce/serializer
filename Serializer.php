<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Bcn\Component\Serializer\Type\TypeFactory;
use Bcn\Component\Serializer\Encoder\EncoderDecoderInterface;
use Bcn\Component\Serializer\Streamer\StreamerInterface;
use Bcn\Component\Serializer\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    /** @var EncoderDecoderInterface */
    protected $encoder;

    /** @var TypeFactory */
    protected $factory;

    /**
     * @param TypeFactory             $factory
     * @param EncoderDecoderInterface $encoder
     */
    public function __construct(TypeFactory $factory, EncoderDecoderInterface $encoder)
    {
        $this->factory = $factory;
        $this->encoder = $encoder;
    }

    /**
     * @param  mixed             $object
     * @param  string            $type
     * @param  array             $options
     * @return StreamerInterface
     */
    public function serialize($object, $type, array $options = array())
    {
        $data = $this->factory->create($type, $options)
                    ->normalize($object);

        return $this->encoder->encode($data);
    }

    /**
     * @param  mixed  $data
     * @param  string $type
     * @param  array  $options
     * @param  mixed  $object
     * @return mixed
     */
    public function unserialize($data, $type, array $options = array(), &$object = null)
    {
        $normalized = $this->encoder->decode($data);

        return $this->factory->create($type, $options)
            ->denormalize($normalized, $object);
    }
}

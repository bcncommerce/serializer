<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Bcn\Component\Serializer\Type\Extension\ExtensionInterface;

class Serializer
{
    /** @var Encoder */
    protected $encoder;

    /** @var Resolver */
    protected $resolver;

    /**
     * @param Resolver   $resolver
     * @param Normalizer $normalizer
     * @param Encoder    $encoder
     */
    public function __construct(Resolver $resolver = null, Normalizer $normalizer = null, Encoder $encoder = null)
    {
        $this->resolver   = $resolver   ?: new Resolver();
        $this->normalizer = $normalizer ?: new Normalizer();
        $this->encoder    = $encoder    ?: new Encoder();
    }

    /**
     * @param  mixed  $object
     * @param  string $type
     * @param  mixed  $stream
     * @param  string $format
     * @param  array  $options
     * @return mixed
     */
    public function serialize($object, $type, &$stream, $format, array $options = array())
    {
        $definition = $this->resolver->getDefinition($type, $options);

        return $this->encoder->encode($object, $definition, $stream, $format);
    }

    /**
     * @param  mixed  $stream
     * @param  string $format
     * @param  string $type
     * @param  array  $options
     * @param  mixed  $object
     * @return mixed
     */
    public function unserialize($stream, $format, $type, array $options = array(), &$object = null)
    {
        $definition = $this->resolver->getDefinition($type, $options);

        return $this->encoder->decode($stream, $format, $definition, $object);
    }

    /**
     * @param  mixed  $object
     * @param  string $type
     * @param  array  $options
     * @return mixed
     */
    public function normalize($object, $type, array $options = array())
    {
        $definition = $this->resolver->getDefinition($type, $options);

        return $this->normalizer->normalize($object, $definition);
    }

    /**
     * @param  mixed      $data
     * @param  string     $type
     * @param  array      $options
     * @param  mixed      $object
     * @return mixed
     * @throws \Exception
     */
    public function denormalize($data, $type, array $options = array(), &$object = null)
    {
        $definition = $this->resolver->getDefinition($type, $options);

        return $this->normalizer->denormalize($data, $definition, $object);
    }

    /**
     * @param  ExtensionInterface $extension
     * @throws \Exception
     */
    public function extend(ExtensionInterface $extension)
    {
        foreach ($extension->getTypes() as $type) {
            $this->resolver->addType($type);
        }

        foreach ($extension->getEncoders() as $encoder) {
            $this->encoder->addFormat($encoder);
        }
    }
}

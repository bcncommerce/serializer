<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

class ArrayNormalizer implements NormalizerInterface
{
    /** @var NormalizerInterface */
    protected $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param  object $object
     * @return $this
     */
    public function normalize($object)
    {
        $data = array();
        foreach ($object as $key => $value) {
            $data[$key] = $this->normalizer->normalize($value);
        }

        return $data;
    }

    /**
     * @param  mixed        $data
     * @param  object       $object
     * @return object|array
     */
    public function denormalize($data, &$object = null)
    {
        if (!$object) {
            $object = array();
        }

        foreach ($data as $key => $value) {
            $object[$key] = $this->normalizer->denormalize($value);
        }

        return $object;
    }

    /**
     * @return NormalizerInterface
     */
    public function getItemNormalizer()
    {
        return $this->normalizer;
    }
}

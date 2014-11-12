<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

class ScalarNormalizer implements NormalizerInterface
{
    /**
     * @param  mixed $object
     * @return mixed
     */
    public function normalize($object)
    {
        return $object;
    }

    /**
     * @param  mixed $data
     * @param  mixed $object
     * @return mixed
     */
    public function denormalize($data, &$object = null)
    {
        $object = $data;

        return $data;
    }
}

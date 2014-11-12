<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

class TextNormalizer implements NormalizerInterface
{
    /**
     * @param  mixed $object
     * @return mixed
     */
    public function normalize($object)
    {
        return (string) $object;
    }

    /**
     * @param  mixed $data
     * @param  mixed $object
     * @return mixed
     */
    public function denormalize($data, &$object = null)
    {
        $object = (string) $data;

        return $data;
    }
}

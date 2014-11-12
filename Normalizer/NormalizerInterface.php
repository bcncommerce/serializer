<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

interface NormalizerInterface
{
    /**
     * @param  mixed $object
     * @return mixed
     */
    public function normalize($object);

    /**
     * @param  mixed $data
     * @param  mixed $object
     * @return mixed
     */
    public function denormalize($data, &$object = null);
}

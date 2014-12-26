<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition;

interface TransformerInterface
{
    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function normalize($value, $origin);

    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function denormalize($value, $origin);
}

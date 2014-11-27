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
     * @param $value
     * @return mixed
     */
    public function normalize($value);

    /**
     * @param $value
     * @return mixed
     */
    public function denormalize($value);
}

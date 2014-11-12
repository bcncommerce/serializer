<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

interface EncoderInterface
{
    /**
     * @param  mixed $data
     * @return mixed
     */
    public function encode($data);

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function decode($data);
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

interface DecoderInterface
{
    /**
     * @param  mixed $data
     * @return mixed
     */
    public function decode($data);
}

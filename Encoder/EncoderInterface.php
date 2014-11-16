<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

use Bcn\Component\Serializer\Encoder\Streamer\StreamerInterface;

interface EncoderInterface
{
    /**
     * @param  mixed             $data
     * @return StreamerInterface
     */
    public function encode($data);
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

abstract class Streamer implements StreamerInterface
{
    /**
     * @return string
     */
    public function __toString()
    {
        $buffer = "";

        $this->stream(function ($data) use (&$buffer) {
            $buffer .= $data;
        });

        return $buffer;
    }
}

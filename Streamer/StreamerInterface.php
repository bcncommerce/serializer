<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Streamer;

interface StreamerInterface
{
    /**
     * @param callback $output
     */
    public function stream($output);

    /**
     * @return string
     */
    public function __toString();
}

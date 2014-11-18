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
     * @param  string|null      $name
     * @param  string|null      $type
     * @return EncoderInterface
     */
    public function node($name = null, $type = null);

    /**
     * @param  string           $value
     * @return EncoderInterface
     */
    public function write($value);

    /**
     * @return EncoderInterface
     */
    public function end();
}

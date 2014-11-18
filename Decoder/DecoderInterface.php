<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Decoder;

interface DecoderInterface
{
    /**
     * Enter a node
     *
     * @param  string|null      $name
     * @param  string|null      $type
     * @return DecoderInterface
     */
    public function node($name = null, $type = null);

    /**
     * Check current pointer in valid position
     *
     * @return boolean
     */
    public function valid();

    /**
     * Get next node on the same level
     *
     * @return boolean
     */
    public function next();

    /**
     * Read a scalar value
     *
     * @return string|boolean|null|integer|float
     */
    public function read();

    /**
     * Leave a node
     *
     * @return DecoderInterface
     */
    public function end();
}

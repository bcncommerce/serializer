<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler;

interface HandlerInterface
{
    /**
     * @param string $name
     * @param array  $attributes
     */
    public function start($name, array $attributes = array());

    /**
     * @param string $name
     */
    public function end($name);

    /**
     * @param string $data
     */
    public function append($data);
}

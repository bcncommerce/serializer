<?php
/**
 * This file is part of the rms project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler\Context;

interface ContextInterface
{
    /**
     * Open sub context node
     *
     * @param  string           $name
     * @param  array            $attributes
     * @return ContextInterface
     */
    public function start($name, array $attributes = array());

    /**
     * Append data to context
     *
     * @param string $data
     */
    public function append($data);

    /**
     * Close sub context
     *
     * @param string $name
     * @param mixed  $value
     */
    public function end($name, $value);

    /**
     * Reset context state
     */
    public function reset();

    /**
     * Fetch context content
     *
     * @return mixed
     */
    public function fetch();
}

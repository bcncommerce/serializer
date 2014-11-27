<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format;

use Bcn\Component\Serializer\Definition;

interface FormatInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param mixed      $origin
     * @param Definition $definition
     * @param resource   $stream
     */
    public function encode($origin, Definition $definition, $stream);

    /**
     * @param  resource   $stream
     * @param  Definition $definition
     * @param  mixed      $origin
     * @return mixed
     */
    public function decode($stream, Definition $definition, &$origin = null);
}

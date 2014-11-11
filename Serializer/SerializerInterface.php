<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Serializer;

interface SerializerInterface
{
    public function serialize($object);

    public function unserialize($data, &$object = null);
}

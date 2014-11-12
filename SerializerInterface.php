<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

interface SerializerInterface
{
    /**
     * @param  mixed  $object
     * @param  string $type
     * @param  array  $options
     * @return mixed
     */
    public function serialize($object, $type, array $options = array());

    /**
     * @param  mixed  $data
     * @param  string $type
     * @param  array  $options
     * @return mixed
     */
    public function unserialize($data, $type, array $options = array());
}

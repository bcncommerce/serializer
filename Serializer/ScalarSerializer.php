<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Serializer;


class ScalarSerializer implements SerializerInterface
{
    /**
     * @param $object
     * @return array
     */
    public function serialize($object)
    {
        return $object;
    }

    /**
     * @param $data
     * @param  null       $object
     * @return array|null
     */
    public function unserialize($data, &$object = null)
    {
        $object = $data;

        return $data;
    }
}

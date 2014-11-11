<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Serializer;

class ArraySerializer implements SerializerInterface
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param  object $object
     * @return $this
     */
    public function serialize($object)
    {
        $data = array();
        foreach ($object as $key => $value) {
            $data[] = $this->serializer->serialize($value);
        }

        return $data;
    }

    /**
     * @param  mixed        $data
     * @param  object       $object
     * @return object|array
     */
    public function unserialize($data, &$object = null)
    {
        if (!$object) {
            $object = array();
        }

        foreach ($data as $key => $value) {
            $object[] = $this->serializer->unserialize($value);
        }

        return $object;
    }

    /**
     * @return SerializerInterface
     */
    public function getItemSerializer()
    {
        return $this->serializer;
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Serializer\ScalarSerializer;
use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Bcn\Component\Serializer\SerializerFactory;

class TextType extends AbstractType
{
    /**
     * @param  SerializerFactory   $factory
     * @param  array               $options
     * @return SerializerInterface
     */
    public function getSerializer(SerializerFactory $factory, array $options = array())
    {
        return new ScalarSerializer();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'text';
    }
}

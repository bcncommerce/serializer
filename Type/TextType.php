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

class TextType extends AbstractType
{
    /**
     * @param  TypeFactory                          $factory
     * @param  array                                $options
     * @return ScalarSerializer|SerializerInterface
     */
    public function getSerializer(TypeFactory $factory, array $options = array())
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

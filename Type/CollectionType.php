<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Bcn\Component\Serializer\Serializer\CollectionSerializer;

class CollectionType implements TypeInterface
{
    /**
     * @param  TypeFactory                              $factory
     * @param  array                                    $options
     * @return CollectionSerializer|SerializerInterface
     */
    public function build(TypeFactory $factory, array $options = array())
    {
        return new CollectionSerializer(
            $factory->create($options['item_type'], $options['item_options'])
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'collection';
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Serializer;
use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Bcn\Component\Serializer\Type\TypeFactory;
use Bcn\Component\Serializer\Type\TypeInterface;

class DocumentType implements TypeInterface
{
    /**
     * @param  TypeFactory         $factory
     * @param  array               $options
     * @return SerializerInterface
     */
    public function build(TypeFactory $factory, array $options = array())
    {
        $serializer = new Serializer('Bcn\Component\Serializer\Tests\Document');
        $serializer
            ->add('name',        $factory->create('text'))
            ->add('description', $factory->create('text'))
            ->add('parent',      $serializer)
        ;

        return $serializer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document';
    }
}

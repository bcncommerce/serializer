<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration\Type;

use Bcn\Component\Serializer\Serializer;
use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Bcn\Component\Serializer\SerializerFactory;
use Bcn\Component\Serializer\Type\AbstractType;

class DocumentType extends AbstractType
{
    /**
     * @param  SerializerFactory   $factory
     * @param  array               $options
     * @return SerializerInterface
     */
    public function getSerializer(SerializerFactory $factory, array $options = array())
    {
        $serializer = new Serializer('Bcn\Component\Serializer\Tests\Integration\Document');
        $serializer
            ->add('name',        $factory->create('text'))
            ->add('description', $factory->create('text'))
            ->add('rank',        $factory->create('number'))
            ->add('rating',      $factory->create('number', array('decimals' => 2)))
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

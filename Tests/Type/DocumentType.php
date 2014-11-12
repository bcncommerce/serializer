<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Normalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Bcn\Component\Serializer\Type\AbstractType;
use Bcn\Component\Serializer\Type\TypeFactory;

class DocumentType extends AbstractType
{
    /**
     * @param  TypeFactory         $factory
     * @param  array               $options
     * @return NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        $normalizer = new Normalizer('Bcn\Component\Serializer\Tests\Document');
        $normalizer
            ->add('name',        $factory->create('text'))
            ->add('description', $factory->create('text'))
            ->add('rank',        $factory->create('number'))
            ->add('rating',      $factory->create('number', array('decimals' => 2)))
            ->add('parent',      $normalizer)
        ;

        return $normalizer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document';
    }
}

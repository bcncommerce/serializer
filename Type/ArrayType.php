<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Serializer\ArraySerializer;
use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Bcn\Component\Serializer\SerializerFactory;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArrayType extends AbstractType
{
    /**
     * @param  SerializerFactory                   $factory
     * @param  array                               $options
     * @return ArraySerializer|SerializerInterface
     */
    public function getSerializer(SerializerFactory $factory, array $options = array())
    {
        return new ArraySerializer(
            $factory->create($options['item_type'], $options['item_options']),
            $options['item_node']
        );
    }

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver)
    {
        $optionsResolver
            ->setRequired(array('item_type'))
            ->setDefaults(array('item_options' => array(), 'item_node' => null))
            ->setAllowedTypes(array(
                'item_type' => array('string', 'Bcn\Component\Serializer\Serializer\SerializerInterface'),
                'item_node' => array('null', 'string'),
                'item_options' => 'array',
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'array';
    }
}

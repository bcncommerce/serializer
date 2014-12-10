<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Definition\Builder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CollectionType extends AbstractType
{
    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array())
    {
        $prototype = $builder
            ->name($options['name'])
            ->prototype($options['item_type'], $options['item_options']);

        if ($options['item_name']) {
            $prototype->name($options['item_name']);
        }
    }

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver)
    {
        $optionsResolver
            ->setRequired(array(
                    'item_type',
                ))
            ->setDefaults(array(
                    'name'         => 'collection',
                    'item_name'    => null,
                    'item_options' => array(),
                ))
            ->setAllowedTypes(array(
                    'name'         => 'string',
                    'item_name'    => array('string', 'null'),
                    'item_type'    => array('string', '\Bcn\Component\Serializer\Definition'),
                    'item_options' => 'array',
                ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'collection';
    }
}

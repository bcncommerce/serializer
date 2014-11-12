<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Bcn\Component\Serializer\Normalizer\CollectionNormalizer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CollectionType extends AbstractType
{
    /**
     * @param  TypeFactory                              $factory
     * @param  array                                    $options
     * @return CollectionNormalizer|NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        $itemNormalizer = $factory->create($options['item_type'], $options['item_options']);
        $normalizer = new CollectionNormalizer($itemNormalizer);

        return $normalizer;
    }

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver)
    {
        $optionsResolver
            ->setRequired(array('item_type'))
            ->setDefaults(array('item_options' => array()))
            ->setAllowedTypes(array(
                'item_type' => array('string', 'Bcn\Component\Serializer\Normalizer\NormalizerInterface'),
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

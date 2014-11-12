<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\ArrayNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArrayType extends AbstractType
{
    /**
     * @param  TypeFactory                         $factory
     * @param  array                               $options
     * @return ArrayNormalizer|NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        $itemNormalizer = $factory->create($options['item_type'], $options['item_options']);
        $normalizer = new ArrayNormalizer($itemNormalizer);

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

        parent::setDefaultOptions($optionsResolver);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'array';
    }
}

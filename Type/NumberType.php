<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\NumberNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NumberType extends AbstractType
{
    /**
     * @param  TypeFactory                          $factory
     * @param  array                                $options
     * @return NumberNormalizer|NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        return new NumberNormalizer(
            $options['decimals'],
            $options['decimal_point'],
            $options['thousand_separator']
        );
    }

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver)
    {
        $optionsResolver
            ->setDefaults(array(
                'decimals'           => 0,
                'decimal_point'      => '.',
                'thousand_separator' => '',
            ))
            ->setAllowedTypes(array(
                'decimals'           => 'numeric',
                'decimal_point'      => 'string',
                'thousand_separator' => 'string',
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'number';
    }
}

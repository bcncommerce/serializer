<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Definition\Builder;
use Bcn\Component\Serializer\Definition\Transformer\NumberTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberType extends AbstractType
{
    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array())
    {
        $builder->transform(new NumberTransformer(
            $options['decimals'],
            $options['decimal_point'],
            $options['thousand_separator']
        ));
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function setDefaultOptions(OptionsResolver $optionsResolver)
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

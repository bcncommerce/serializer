<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Serializer\ScalarSerializer;
use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NumberType extends AbstractType
{
    /**
     * @param  TypeFactory                          $factory
     * @param  array                                $options
     * @return ScalarSerializer|SerializerInterface
     */
    public function getSerializer(TypeFactory $factory, array $options = array())
    {
        $serializer = new ScalarSerializer();

        $serializer->setNormalizer(function ($value) use ($options) {
            $value = number_format(
                $value,
                $options['decimals'],
                $options['decimal_point'],
                $options['thousand_separator']
            );

            if ($options['decimal_point'] == '.' && $options['thousand_separator'] == '') {
                $value = ($options['decimals'] === 0) ? intval($value) : floatval($value);
            }

            return $value;
        });

        $serializer->setDenormalizer(function ($value) use ($options) {
            $value = str_replace($options['thousand_separator'], '', $value);
            $value = str_replace($options['decimal_point'], '.', $value);

            return ($options['decimals'] === 0) ? intval($value) : floatval($value);
        });

        return $serializer;
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

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\IteratorNormalizer;
use Bcn\Component\Serializer\Normalizer\DatetimeNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatetimeType extends AbstractType
{
    /**
     * @param  TypeFactory                            $factory
     * @param  array                                  $options
     * @return IteratorNormalizer|NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        return new DatetimeNormalizer($options['format']);
    }

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver)
    {
        $optionsResolver
            ->setDefaults(array('format' => \DateTime::ISO8601))
            ->setAllowedTypes(array(
                'format' => 'string',
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datetime';
    }
}

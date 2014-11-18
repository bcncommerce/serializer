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

class DatetimeType extends AbstractType
{
    /**
     * @param  TypeFactory                          $factory
     * @param  array                                $options
     * @return ScalarSerializer|SerializerInterface
     */
    public function getSerializer(TypeFactory $factory, array $options = array())
    {
        $serializer = new ScalarSerializer();

        $serializer->setNormalizer(function (\DateTime $value) use ($options) {
            return $value->format($options['format']);
        });

        $serializer->setDenormalizer(function ($value) use ($options) {
            return \DateTime::createFromFormat($options['format'], $value);
        });

        return $serializer;
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

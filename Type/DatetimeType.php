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
use Bcn\Component\Serializer\Definition\Transformer\DatetimeTransformer;

class DatetimeType extends AbstractType
{
    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array())
    {
        $builder->transform(new DatetimeTransformer($options['format']));
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

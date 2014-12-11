<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Definition\Builder;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array());

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function setDefaultOptions(OptionsResolver $optionsResolver);
}

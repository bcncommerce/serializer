<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

interface TypeInterface
{
    /**
     * @param  TypeFactory         $factory
     * @param  array               $options
     * @return NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array());

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver);

    /**
     * @return string
     */
    public function getName();
}

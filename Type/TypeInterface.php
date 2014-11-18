<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Serializer\SerializerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

interface TypeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param  TypeFactory         $factory
     * @param  array               $options
     * @return SerializerInterface
     */
    public function getSerializer(TypeFactory $factory, array $options = array());

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setDefaultOptions(OptionsResolverInterface $optionsResolver);
}

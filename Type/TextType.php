<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\TextNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

class TextType extends AbstractType
{
    /**
     * @param  TypeFactory                        $factory
     * @param  array                              $options
     * @return TextNormalizer|NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        return new TextNormalizer();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'text';
    }
}

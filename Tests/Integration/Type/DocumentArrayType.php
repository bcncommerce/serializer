<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration\Type;

use Bcn\Component\Serializer\Normalizer\NormalizerInterface;
use Bcn\Component\Serializer\Type\AbstractType;
use Bcn\Component\Serializer\Type\TypeFactory;

class DocumentArrayType extends AbstractType
{
    /**
     * @param  TypeFactory         $factory
     * @param  array               $options
     * @return NormalizerInterface
     */
    public function getNormalizer(TypeFactory $factory, array $options = array())
    {
        return $factory->create('array', array('item_type' => 'document'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document_array';
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextTypeTest extends TestCase
{
    const TYPE_NAME        = 'text';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\TextNormalizer';

    public function testGetName()
    {
        $type = new TextType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $factory = $this->getTypeFactoryMock();

        $type = new TextType();
        $normalizer = $type->getNormalizer($factory);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
    }

    public function testAllowedOptions()
    {
        $resolver = new OptionsResolver();
        $type = new TextType();
        $type->setDefaultOptions($resolver);

        $resolver->resolve(array());
    }

    public function testDefaultOptions()
    {
        $resolver = new OptionsResolver();
        $type = new TextType();
        $type->setDefaultOptions($resolver);

        $options = $resolver->resolve(array());

        $this->assertEquals(array(), array_keys($options));
    }
}

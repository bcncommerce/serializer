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
    public function testGetSerializer()
    {
        $type = new TextType();
        $serializer = $type->getSerializer($this->getTypeFactoryMock());

        $this->assertInstanceOf('Bcn\Component\Serializer\Serializer\ScalarSerializer', $serializer);
    }

    public function testGetName()
    {
        $type = new TextType();

        $this->assertEquals('text', $type->getName());
    }

    public function testGetDefaultOptions()
    {
        $optionResolver = new OptionsResolver();

        $type = new TextType();
        $type->setDefaultOptions($optionResolver);

        $this->assertEquals(
            array(),
            array_keys($optionResolver->resolve(array()))
        );
    }
}

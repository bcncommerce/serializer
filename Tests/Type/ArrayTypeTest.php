<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\ArrayType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrayTypeTest extends TestCase
{
    const TYPE_NAME        = 'array';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\ArrayNormalizer';

    public function testGetName()
    {
        $type = new ArrayType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $itemNoermalizer = $this->getNormalizerMock();

        $factory = $this->getTypeFactoryMock();
        $factory->expects($this->once())
            ->method('create')
            ->with($this->equalTo('text'))
            ->will($this->returnValue($itemNoermalizer));

        $options = array('item_type' => 'text', 'item_options' => array());

        $type = new ArrayType();
        $normalizer = $type->build($factory, $options);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
        $this->assertSame($itemNoermalizer, $normalizer->getItemNormalizer());
    }

    public function testAllowedOptions()
    {
        $resolver = new OptionsResolver();
        $type = new ArrayType();
        $type->setDefaultOptions($resolver);

        $resolver->resolve(array(
            'item_type'    => 'text',
            'item_options' => array(),
        ));
    }

    public function testDefaultOptions()
    {
        $resolver = new OptionsResolver();
        $type = new ArrayType();
        $type->setDefaultOptions($resolver);

        $options = $resolver->resolve(array('item_type' => 'text'));

        $this->assertEquals(array('item_options', 'item_type'), array_keys($options));
    }
}

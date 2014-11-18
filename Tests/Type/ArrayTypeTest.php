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
    public function testGetSerializer()
    {
        $itemSerializer = $this->getSerializerMock();

        $factory = $this->getSerializerFactoryMock();
        $factory->expects($this->once())
            ->method('create')
            ->with($this->equalTo('test'), $this->equalTo(array()))
            ->will($this->returnValue($itemSerializer));

        $type = new ArrayType();
        $serializer = $type->getSerializer($factory, array(
            'item_type'    => 'test',
            'item_node'    => 'item',
            'item_options' => array(),
        ));

        $this->assertInstanceOf('Bcn\Component\Serializer\Serializer\ArraySerializer', $serializer);
        $this->assertSame($itemSerializer, $serializer->getItemSerializer());
    }

    public function testGetName()
    {
        $type = new ArrayType();

        $this->assertEquals('array', $type->getName());
    }

    public function testGetDefaultOptions()
    {
        $optionResolver = new OptionsResolver();

        $type = new ArrayType();
        $type->setDefaultOptions($optionResolver);

        $this->assertEquals(
            array('item_options', 'item_node', 'item_type'),
            array_keys($optionResolver->resolve(array('item_type' => 'text')))
        );
    }
}

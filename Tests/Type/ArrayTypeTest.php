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

class ArrayTypeTest extends TestCase
{
    const TYPE_NAME        = 'array';
    const SERIALIZER_CLASS = 'Bcn\Component\Serializer\Serializer\ArraySerializer';

    public function testGetName()
    {
        $type = new ArrayType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $itemSerializer = $this->getSerializerMock();

        $factory = $this->getFactoryMock();
        $factory->expects($this->once())
            ->method('create')
            ->with($this->equalTo('text'))
            ->will($this->returnValue($itemSerializer));

        $options = array('item_type' => 'text', 'item_options' => array());

        $type = new ArrayType();
        $serializer = $type->build($factory, $options);

        $this->assertInstanceOf(self::SERIALIZER_CLASS, $serializer);
        $this->assertSame($itemSerializer, $serializer->getItemSerializer());
    }
}

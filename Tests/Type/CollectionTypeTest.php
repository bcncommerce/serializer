<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\CollectionType;

class CollectionTypeTest extends TestCase
{
    const TYPE_NAME        = 'collection';
    const SERIALIZER_CLASS = 'Bcn\Component\Serializer\Serializer\CollectionSerializer';

    public function testGetName()
    {
        $type = new CollectionType();

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

        $type = new CollectionType();
        $serializer = $type->build($factory, $options);

        $this->assertInstanceOf(self::SERIALIZER_CLASS, $serializer);
        $this->assertSame($itemSerializer, $serializer->getItemSerializer());
    }
}

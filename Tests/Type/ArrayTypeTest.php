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
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\ArrayNormalizer';

    public function testGetName()
    {
        $type = new ArrayType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $itemNoermalizer = $this->getNormalizerMock();

        $factory = $this->getFactoryMock();
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
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Definition;

class DefinitionTest extends TestCase
{
    public function testIsScalar()
    {
        $definition = new Definition();

        $this->assertTrue($definition->isScalar());
        $this->assertFalse($definition->isArray());
        $this->assertFalse($definition->isObject());
    }

    public function testIsArray()
    {
        $definition = new Definition();
        $definition->setPrototype($this->getDefinitionMock());

        $this->assertFalse($definition->isScalar());
        $this->assertTrue($definition->isArray());
        $this->assertFalse($definition->isObject());
    }

    public function testIsObject()
    {
        $definition = new Definition();
        $definition->addProperty('foo', $this->getDefinitionMock());

        $this->assertFalse($definition->isScalar());
        $this->assertFalse($definition->isArray());
        $this->assertTrue($definition->isObject());
    }

    public function testGetNodeName()
    {
        $definition = new Definition();
        $definition->setNodeName('foo');

        $this->assertEquals('foo', $definition->getNodeName());
    }

    public function testAddProperty()
    {
        $definition = new Definition();
        $definition->addProperty('foo', $this->getDefinitionMock());

        $this->assertTrue($definition->hasProperty('foo'));
    }

    public function testAddPropertyException()
    {
        $this->setExpectedException('Exception');

        $definition = new Definition();
        $definition->addProperty('foo', $this->getDefinitionMock());
        $definition->addProperty('foo', $this->getDefinitionMock());
    }

    public function testGetProperty()
    {
        $property = $this->getDefinitionMock();

        $definition = new Definition();
        $definition->addProperty('foo', $property);

        $this->assertSame($property, $definition->getProperty('foo'));
    }

    public function testGetPropertyException()
    {
        $this->setExpectedException('Exception');

        $definition = new Definition();
        $definition->getProperty('foo');
    }

    public function testGetProperties()
    {
        $properties = array(
            'foo' => $this->getDefinitionMock(),
            'baz' => $this->getDefinitionMock(),
        );

        $definition = new Definition();
        $definition->addProperty('foo', $properties['foo']);
        $definition->addProperty('baz', $properties['baz']);

        $this->assertEquals($properties, $definition->getProperties());
    }

    public function testSetPrototype()
    {
        $prototype = $this->getDefinitionMock();

        $definition = new Definition();
        $definition->setPrototype($prototype);

        $this->assertSame($prototype, $definition->getPrototype());
    }

    public function testCreateWithoutFactory()
    {
        $definition = new Definition();

        $this->assertNull($definition->create(null));
    }

    public function testCreateWithFactory()
    {
        $definition = new Definition();
        $definition->setFactory(function ($origin) {
            return $origin;
        });

        $this->assertEquals('foo', $definition->create('foo'));
    }

    public function testExtract()
    {
        $object = $this->getDocument();

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('getValue')
            ->with($object, 'path')
            ->will($this->returnValue('foo'));

        $definition = new Definition();
        $definition->setPropertyAccessor($accessor);
        $definition->setPropertyPath('path');

        $this->assertEquals('foo', $definition->extract($object));
    }

    public function testSettle()
    {
        $object = $this->getDocument();

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('setValue')
            ->with($object, 'path', 'foo');

        $definition = new Definition();
        $definition->setPropertyAccessor($accessor);
        $definition->setPropertyPath('path');

        $definition->settle($object, 'foo');
    }

    public function testExtractNormalize()
    {
        $object = $this->getDocument();

        $transformer = $this->getTransformerMock();
        $transformer->expects($this->once())
            ->method('normalize')
            ->with('baz')
            ->will($this->returnValue('foo'));

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('getValue')
            ->with($object, 'path')
            ->will($this->returnValue('baz'));

        $definition = new Definition();
        $definition->setPropertyAccessor($accessor);
        $definition->setPropertyPath('path');
        $definition->setTransformer($transformer);

        $this->assertEquals('foo', $definition->extract($object));
    }

    public function testSettleDenormalize()
    {
        $object = $this->getDocument();

        $transformer = $this->getTransformerMock();
        $transformer->expects($this->once())
            ->method('denormalize')
            ->with('baz')
            ->will($this->returnValue('foo'));

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('setValue')
            ->with($object, 'path', 'foo');

        $definition = new Definition();
        $definition->setPropertyAccessor($accessor);
        $definition->setPropertyPath('path');
        $definition->setTransformer($transformer);

        $definition->settle($object, 'baz');
    }
}

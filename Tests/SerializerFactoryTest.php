<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\SerializerFactory;

class SerializerFactoryTest extends TestCase
{
    public function testCreateByName()
    {
        $factory = new SerializerFactory();
        $serializer = $this->getSerializerMock();
        $options = array();

        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));
        $type->expects($this->once())
            ->method('getSerializer')
            ->with($this->equalTo($factory), $this->equalTo($options))
            ->will($this->returnValue($serializer));

        $factory->addType($type);

        $actual = $factory->create('test', $options);

        $this->assertSame($serializer, $actual);
    }

    public function testCreateByInstance()
    {
        $factory = new SerializerFactory();
        $serializer = $this->getSerializerMock();
        $options = array();

        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getSerializer')
            ->with($this->equalTo($factory), $this->equalTo($options))
            ->will($this->returnValue($serializer));

        $actual = $factory->create($type, $options);

        $this->assertSame($serializer, $actual);
    }

    public function testCreateUnknownTypeException()
    {
        $this->setExpectedException('Exception');

        $factory = new SerializerFactory();
        $factory->create('test');
    }

    public function testGetType()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new SerializerFactory();
        $factory->addType($type);

        $this->assertSame($type, $factory->getType('test'));
    }

    public function testGetTypeException()
    {
        $this->setExpectedException('Exception');

        $factory = new SerializerFactory();
        $factory->getType('test');
    }

    public function testHasTypeByName()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new SerializerFactory();
        $factory->addType($type);

        $this->assertTrue($factory->hasType('test'));
    }

    public function testAddTypeException()
    {
        $this->setExpectedException('Exception');

        $type = $this->getTypeMock();
        $type->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new SerializerFactory();
        $factory->addType($type);
        $factory->addType($type);
    }

    public function testDoesNotHaveTypeByName()
    {
        $factory = new SerializerFactory();

        $this->assertFalse($factory->hasType('test'));
    }

    public function testHasTypeByInstance()
    {
        $type = $this->getTypeMock();
        $type->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new SerializerFactory();
        $factory->addType($type);

        $this->assertTrue($factory->hasType($type));
    }

    public function testDoesNotHaveTypeByInstance()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new SerializerFactory();

        $this->assertFalse($factory->hasType($type));
    }

    public function testExtend()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $extension = $this->getTypeExtension();
        $extension->expects($this->once())
            ->method('getTypes')
            ->will($this->returnValue(array($type)));

        $factory = new SerializerFactory();
        $factory->extend($extension);

        $this->assertTrue($factory->hasType('test'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTypeExtension()
    {
        return $this->getMock('Bcn\Component\Serializer\Type\Extension\TypeExtensionInterface');
    }
}

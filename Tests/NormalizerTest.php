<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Normalizer;

class NormalizerTest extends TestCase
{
    public function testNormalizeScalar()
    {
        $definition = $this->getDefinitionMock();
        $definition->expects($this->any())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('extract')
            ->with("original")
            ->will($this->returnValue("normalized"));

        $normalizer = new Normalizer();

        $normalized = $normalizer->normalize("original", $definition);

        $this->assertEquals("normalized", $normalized);
    }

    public function testNormalizeArray()
    {
        $original   = array('one', 'two', 'three');
        $normalized = array(3, 3, 5);

        $prototype = $this->getDefinitionMock();
        $prototype->expects($this->any())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $prototype->expects($this->atLeastOnce())
            ->method('extract')
            ->will($this->returnCallback(function ($item) {
                return strlen($item);
            }));

        $definition = $this->getDefinitionMock();
        $definition->expects($this->any())
            ->method('isArray')
            ->will($this->returnValue(true));
        $definition->expects($this->atLeastOnce())
            ->method('getPrototype')
            ->will($this->returnValue($prototype));
        $definition->expects($this->once())
            ->method('extract')
            ->with($original)
            ->will($this->returnValue($original));

        $normalizer = new Normalizer();

        $this->assertEquals($normalized, $normalizer->normalize($original, $definition));
    }

    public function testNormalizeObject()
    {
        $original   = $this->getDocument('object');
        $normalized = array('name' => 'Test name object');

        $nameDefinition = $this->getDefinitionMock();
        $nameDefinition->expects($this->any())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $nameDefinition->expects($this->atLeastOnce())
            ->method('extract')
            ->will($this->returnCallback(function (Document $document) {
                return $document->getName();
            }));

        $definition = $this->getDefinitionMock();
        $definition->expects($this->any())
            ->method('isObject')
            ->will($this->returnValue(true));
        $definition->expects($this->atLeastOnce())
            ->method('getProperties')
            ->will($this->returnValue(array('name' => $nameDefinition)));
        $definition->expects($this->once())
            ->method('extract')
            ->with($original)
            ->will($this->returnValue($original));

        $normalizer = new Normalizer();

        $this->assertEquals($normalized, $normalizer->normalize($original, $definition));
    }

    public function testNormalizeNull()
    {
        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isObject')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('extract')
            ->will($this->returnValue(null));

        $normalizer = new Normalizer();

        $this->assertNull($normalizer->normalize(null, $definition));
    }

    public function testDenormalizeScalar()
    {
        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('settle')
            ->with($this->anything(), 'normalized')
            ->will($this->returnCallback(function (&$origin) {
                $origin = 'original';
            }));

        $normalizer = new Normalizer();
        $denormalized = $normalizer->denormalize('normalized', $definition);

        $this->assertEquals('original', $denormalized);
    }

    public function testDenormalizeArray()
    {
        $prototype = $this->getDefinitionMock();
        $prototype->expects($this->any())
            ->method('create')
            ->will($this->returnValue(null));
        $prototype->expects($this->any())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $prototype->expects($this->any())
            ->method('settle')
            ->will($this->returnCallback(function (&$origin, $value) {
                $origin = strlen($value);
            }));

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isArray')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('create')
            ->will($this->returnCallback(function () {
                return new \ArrayObject();
            }));
        $definition->expects($this->once())
            ->method('settle')
            ->will($this->returnCallback(function (&$origin, $value) {
                $origin = $value;
            }));
        $definition->expects($this->any())
            ->method('getPrototype')
            ->will($this->returnValue($prototype));

        $normalizer = new Normalizer();
        $denormalized = $normalizer->denormalize(array('one', 'two', 'three'), $definition);

        $this->isInstanceOf("ArrayObject", $denormalized);
        $this->assertEquals(array(3, 3, 5), $denormalized->getArrayCopy());
    }

    public function testDenormalizeObject()
    {
        $nameDefinition = $this->getDefinitionMock();
        $nameDefinition->expects($this->any())
            ->method('create')
            ->will($this->returnValue(null));
        $nameDefinition->expects($this->any())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $nameDefinition->expects($this->any())
            ->method('settle')
            ->will($this->returnCallback(function (&$origin, $value) {
                $origin->setName($value);
            }));

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isObject')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('create')
            ->will($this->returnValue(new Document()));
        $definition->expects($this->any())
            ->method('getProperties')
            ->will($this->returnValue(array('name' => $nameDefinition)));
        $definition->expects($this->any())
            ->method('settle')
            ->will($this->returnCallback(function (&$origin, $value) {
                $origin = $value;
            }));

        $normalizer = new Normalizer();
        $denormalized = $normalizer->denormalize(array('name' => "Document"), $definition);

        $this->isInstanceOf(self::DOCUMENT_CLASS, $denormalized);
        $this->assertEquals("Document", $denormalized->getName());
    }

    public function testDenormalizeNull()
    {
        $origin = new \stdClass();

        $normalizer   = new Normalizer();

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isObject')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('settle')
            ->with($origin, null);

        $normalizer->denormalize(null, $definition, $origin);
    }
}

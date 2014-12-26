<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Encoder;

class EncoderTest extends TestCase
{
    public function testAddEncoder()
    {
        $format = $this->getFormatMock();
        $format->expects($this->once())
            ->method('getNames')
            ->will($this->returnValue(array('foo')));

        $encoder = new Encoder();
        $encoder->addFormat($format);

        $encoder->getFormat('foo');
    }

    public function testAddEncoderTwiceException()
    {
        $this->setExpectedException('Exception');

        $format = $this->getFormatMock();
        $format->expects($this->exactly(2))
            ->method('getNames')
            ->will($this->returnValue(array('foo')));

        $encoder = new Encoder();
        $encoder->addFormat($format);
        $encoder->addFormat($format);
    }

    public function testGetEncoder()
    {
        $format = $this->getFormatMock();
        $format->expects($this->once())
            ->method('getNames')
            ->will($this->returnValue(array('foo')));

        $encoder = new Encoder();
        $encoder->addFormat($format);

        $this->assertSame($format, $encoder->getFormat('foo'));
    }

    public function testGetEncoderException()
    {
        $this->setExpectedException('Exception');

        $encoder = new Encoder();
        $encoder->getFormat('foo');
    }

    public function testEncode()
    {
        $stream     = $this->getDataStream();
        $definition = $this->getDefinitionMock();
        $origin     = new \stdClass();

        $format = $this->getFormatMock();
        $format->expects($this->any())
            ->method('getNames')
            ->will($this->returnValue(array('foo')));
        $format->expects($this->once())
            ->method('encode')
            ->with($origin, $definition, $stream);

        $encoder = new Encoder();
        $encoder->addFormat($format);

        $encoder->encode($origin, $definition, $stream, 'foo');
    }

    public function testDecode()
    {
        $stream     = $this->getDataStream();
        $definition = $this->getDefinitionMock();
        $origin     = new \stdClass();
        $decoded    = new \stdClass();

        $format = $this->getFormatMock();
        $format->expects($this->any())
            ->method('getNames')
            ->will($this->returnValue(array('foo')));
        $format->expects($this->once())
            ->method('decode')
            ->with($stream, $definition, $origin)
            ->will($this->returnValue($decoded));

        $encoder = new Encoder();
        $encoder->addFormat($format);

        $result = $encoder->decode($stream, 'foo', $definition, $origin);

        $this->assertSame($decoded, $result);
    }

    public function testEncodeAndReturn()
    {
        $format = $this->getFormatMock();
        $format->expects($this->any())
            ->method('getNames')
            ->will($this->returnValue(array('foo')));
        $format->expects($this->once())
            ->method('encode')
            ->will($this->returnCallback(function ($origin, $definition, $stream) {
                fputs($stream, "bar");
            }));

        $encoder = new Encoder();
        $encoder->addFormat($format);

        $content = $encoder->encode(null, $this->getDefinitionMock(), false, 'foo');

        $this->assertEquals('bar', $content);
    }
}

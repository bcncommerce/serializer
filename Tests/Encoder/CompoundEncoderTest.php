<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Encoder;

use Bcn\Component\Serializer\Encoder\CompoundEncoder;
use Bcn\Component\Serializer\Tests\TestCase;

class CompoundEncoderTest extends TestCase
{
    public function testEncode()
    {
        $encoder = $this->getEncoderMock();
        $encoder->expects($this->once())
            ->method('encode')
            ->with($this->equalTo('decoded'))
            ->will($this->returnValue('encoded'));

        $decoder = $this->getDecoderMock();

        $compound = new CompoundEncoder($encoder, $decoder);
        $encoded = $compound->encode('decoded');

        $this->assertEquals('encoded', $encoded);
    }

    public function testDecode()
    {
        $encoder = $this->getEncoderMock();

        $decoder = $this->getDecoderMock();
        $decoder->expects($this->once())
            ->method('decode')
            ->with($this->equalTo('encoded'))
            ->will($this->returnValue('decoded'));

        $compound = new CompoundEncoder($encoder, $decoder);
        $decoded = $compound->decode('encoded');

        $this->assertEquals('decoded', $decoded);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEncoderMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Encoder\EncoderInterface')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDecoderMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Encoder\DecoderInterface')
            ->getMock();
    }
}

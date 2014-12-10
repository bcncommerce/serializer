<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Format;

use Bcn\Component\Serializer\Format\JsonFormat;
use Bcn\Component\Serializer\Tests\TestCase;

class JsonFormatTest extends TestCase
{
    /**
     *
     */
    public function testGetNames()
    {
        $format = new JsonFormat();

        $names = $format->getNames();

        $this->assertContains('json', $names);
        $this->assertContains('application/json', $names);
        $this->assertContains('text/json', $names);
    }

    /**
     * @param mixed  $decoded
     * @param string $encoded
     * @dataProvider provideEncodedDecodedValues
     */
    public function testEncode($decoded, $encoded)
    {
        $origin = new \stdClass();

        $definition = $this->getDefinitionMock();

        $normalizer = $this->getNormalizerMock();
        $normalizer->expects($this->once())
            ->method('normalize')
            ->with($origin, $definition)
            ->will($this->returnValue($decoded));

        $stream = $this->getDataStream();

        $encoder = new JsonFormat(0, $normalizer);
        $encoder->encode($origin, $definition, $stream);

        rewind($stream);

        $this->assertJsonStringEqualsJsonString($encoded, stream_get_contents($stream));
    }

    /**
     * @param mixed  $decoded
     * @param string $encoded
     * @dataProvider provideEncodedDecodedValues
     */
    public function testDecode($decoded, $encoded)
    {
        $origin = new \stdClass();

        $definition = $this->getDefinitionMock();

        $normalizer = $this->getNormalizerMock();
        $normalizer->expects($this->once())
            ->method('denormalize')
            ->with($decoded, $definition, $origin);

        $stream = $this->getDataStream($encoded);

        $encoder = new JsonFormat(0, $normalizer);
        $encoder->decode($stream, $definition, $origin);
    }

    /**
     * @return array
     */
    public function provideEncodedDecodedValues()
    {
        return array(
            'structure' => array(
                array('first' => 'one', 'second' => 'two'),
                '{"first": "one", "second": "two"}',
            ),
            'array' => array(
                array('one', 'two'),
                '["one", "two"]',
            ),
            'scalar' => array(
                'foo',
                '"foo"',
            ),
        );
    }
}

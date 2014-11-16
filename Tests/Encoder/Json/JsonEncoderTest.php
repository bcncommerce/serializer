<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Json;

use Bcn\Component\Serializer\Encoder\Json\JsonEncoder;
use Bcn\Component\Serializer\Tests\TestCase;

class JsonEncoderTest extends TestCase
{
    /**
     * @param $encoded
     * @param $decoded
     * @dataProvider provideEncodedAndDecodedTestCases
     */
    public function testEncode($encoded, $decoded)
    {
        $encoder = new JsonEncoder();
        $this->assertEquals($encoded, $encoder->encode($decoded));
    }

    /**
     * @param $encoded
     * @param $decoded
     * @dataProvider provideEncodedAndDecodedTestCases
     */
    public function testDecode($encoded, $decoded)
    {
        $encoder = new JsonEncoder();
        $this->assertEquals($decoded, $encoder->decode($encoded));
    }

    /**
     *
     */
    public function provideEncodedAndDecodedTestCases()
    {
        $types = array(
            'String'                         => "foo",
            'String with single quote'       => "'string'",
            'String with double quote'       => '"string"',
            'String with special characters' => "!@#$%^&*()_¡™£¢∞§¶•ªº–",
            'Integer'                        => 123,
            'Float'                          => 11.5,
            'Array'                          => array(1, 2, 3),
            'Assoc array'                    => array('foo' => 1, 'bar' => 2),
            'Nested array'                   => array(array(1, 2, 3), array(1), array('foo' => 1, 'bar' => 2)),
        );

        $cases = array();
        foreach ($types as $name => $data) {
            $cases[$name] = array(json_encode($data), $data);
        }

        return $cases;
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Definition\Transformer;

use Bcn\Component\Serializer\Definition\Transformer\KeyValueTransformer;
use Bcn\Component\Serializer\Tests\TestCase;

class KeyValueTransformerTest extends TestCase
{
    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testNormalize($denormalized, $normalized)
    {
        $transformer = new KeyValueTransformer();
        $this->assertEquals($normalized, $transformer->normalize($denormalized));
    }
    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testDenormalize($denormalized, $normalized)
    {
        $transformer = new KeyValueTransformer();
        $this->assertEquals($denormalized, $transformer->denormalize($normalized));
    }

    /**
     * @return array
     */
    public function provideNormalizedAndDenormalized()
    {
        return array(
            'Data Set' => array(
                array(
                    'A' => 'a',
                    'B' => 'b',
                ),
                array(
                    array('key' => 'A', 'value' => 'a'),
                    array('key' => 'B', 'value' => 'b'),
                ),
            ),
        );
    }
}

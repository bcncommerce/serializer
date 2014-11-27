<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Definition\Transformer;

use Bcn\Component\Serializer\Definition\Transformer\NumberTransformer;
use Bcn\Component\Serializer\Tests\TestCase;

class NumberTransformerTest extends TestCase
{
    /**
     * @param $denormalized
     * @param $normalized
     * @param $decimals
     * @param $point
     * @param $thousand
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testNormalize($denormalized, $normalized, $decimals, $point, $thousand)
    {
        $transformer = new NumberTransformer($decimals, $point, $thousand);
        $this->assertEquals($normalized, $transformer->normalize($denormalized));
    }
    /**
     * @param $denormalized
     * @param $normalized
     * @param $decimals
     * @param $point
     * @param $thousand
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testDenormalize($denormalized, $normalized, $decimals, $point, $thousand)
    {
        $transformer = new NumberTransformer($decimals, $point, $thousand);
        $this->assertEquals($denormalized, $transformer->denormalize($normalized));
    }

    /**
     * @return array
     */
    public function provideNormalizedAndDenormalized()
    {
        return array(
            'Integer'                         => array(1022,    1022,        0, '.', ''),
            'Integer with thousand separator' => array(1022000, '1*022*000', 0, '.', '*'),
            'Float'                           => array(102.2,   102.2,       1, '.', ''),
            'Float with decimal separator'    => array(102.2,   '102#2',     1, '#', '*'),
            'Float with thousand separator'   => array(11202.2, '11*202#2',  1, '#', '*'),
        );
    }
}

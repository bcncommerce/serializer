<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Normalizer\NumberNormalizer;

class NumberNormalizerTest extends TestCase
{
    /**
     * @param $normalized
     * @param $denormalized
     * @param $decimals
     * @param $decPoint
     * @param $thSep
     * @dataProvider provideValues
     */
    public function testNormalize($normalized, $denormalized, $decimals, $decPoint, $thSep)
    {
        $normalizer = new NumberNormalizer($decimals, $decPoint, $thSep);
        $actual = $normalizer->normalize($denormalized);

        $this->assertEquals($normalized, $actual);
    }

    /**
     * @param $normalized
     * @param $denormalized
     * @param $decimals
     * @param $decPoint
     * @param $thSep
     * @dataProvider provideValues
     */
    public function testDenormalize($normalized, $denormalized, $decimals, $decPoint, $thSep)
    {
        $normalizer = new NumberNormalizer($decimals, $decPoint, $thSep);
        $actual = $normalizer->denormalize($normalized);

        $this->assertEquals($denormalized, $actual);
    }

    /**
     * @param $normalized
     * @param $denormalized
     * @param $decimals
     * @param $decPoint
     * @param $thSep
     * @dataProvider provideValues
     */
    public function testDenormalizeToVariable($normalized, $denormalized, $decimals, $decPoint, $thSep)
    {
        $normalizer = new NumberNormalizer($decimals, $decPoint, $thSep);
        $actual = null;
        $normalizer->denormalize($normalized, $actual);

        $this->assertEquals($denormalized, $actual);
    }

    /**
     * @return array
     */
    public function provideValues()
    {
        return array(
            'Integer'                         => array(1022,    '1022',     0, '.', ''),
            'Integer with thousand separator' => array(1022,    '1*022',    0, '.', '*'),
            'Float'                           => array(102.2,   '102.2',    1, '.', ''),
            'Float with decimal separator'    => array(102.2,   '102#2',    1, '#', '*'),
            'Float with thousand separator'   => array(11202.2, '11*202#2', 1, '#', '*'),
        );
    }
}

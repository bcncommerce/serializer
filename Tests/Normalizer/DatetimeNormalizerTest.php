<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Normalizer\DatetimeNormalizer;
use Bcn\Component\Serializer\Tests\TestCase;

class DatetimeNormalizerTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     */
    public function testNormalizeException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $normalizer = new DatetimeNormalizer();
        $normalizer->normalize(1262615180);
    }

    /**
     * @param $normalized
     * @param $denormalized
     * @param $format
     * @dataProvider provideValues
     */
    public function testNormalize($normalized, $denormalized, $format)
    {
        $normalizer = new DatetimeNormalizer($format);
        $actual = $normalizer->normalize($denormalized);

        $this->assertEquals($normalized, $actual);
    }

    /**
     * @param $normalized
     * @param $denormalized
     * @param $format
     * @dataProvider provideValues
     */
    public function testDenormalize($normalized, $denormalized, $format)
    {
        $normalizer = new DatetimeNormalizer($format);
        $actual = $normalizer->denormalize($normalized);

        $this->assertEquals($denormalized, $actual);
    }

    /**
     * @param $normalized
     * @param $denormalized
     * @param $format
     * @dataProvider provideValues
     */
    public function testDenormalizeToVariable($normalized, $denormalized, $format)
    {
        $normalizer = new DatetimeNormalizer($format);
        $actual = null;
        $normalizer->denormalize($normalized, $actual);

        $this->assertEquals($denormalized, $actual);
    }

    /**
     * @return array
     */
    public function provideValues()
    {
        $date = new \DateTime('Mon Jan 4 14:26:20 2010 +0000');

        return array(
            'ISO8601'       => array('2010-01-04T14:26:20+0000',            $date, 'Y-m-d\TH:i:sO'),
            'Cookie format' => array('Monday, 04-Jan-10 14:26:20 GMT+0000', $date, 'l, d-M-y H:i:s T'),
            'Unix time'     => array('1262615180',                          $date, 'U'),
        );
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Definition\Transformer;

use Bcn\Component\Serializer\Definition\Transformer\DatetimeTransformer;
use Bcn\Component\Serializer\Tests\TestCase;

class DatetimeTransformerTest extends TestCase
{
    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testNormalize($denormalized, $normalized, $format)
    {
        $transformer = new DatetimeTransformer($format);
        $this->assertEquals($normalized, $transformer->normalize($denormalized));
    }
    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testDenormalize($denormalized, $normalized, $format)
    {
        $transformer = new DatetimeTransformer($format);
        $this->assertEquals($denormalized, $transformer->denormalize($normalized));
    }

    /**
     * @return array
     */
    public function provideNormalizedAndDenormalized()
    {
        $date = new \DateTime('Mon Jan 4 14:26:20 2010 +0000');

        return array(
            'ISO8601'  => array($date, '2010-01-04T14:26:20+0000',            'Y-m-d\TH:i:sO'),
            'Cookie'   => array($date, 'Monday, 04-Jan-10 14:26:20 GMT+0000', 'l, d-M-y H:i:s T'),
            'Unixtime' => array($date, '1262615180',                          'U'),
            'Null'     => array(null,  null,                                  'U'),
        );
    }
}

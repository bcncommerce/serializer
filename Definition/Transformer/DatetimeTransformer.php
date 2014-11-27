<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition\Transformer;

use Bcn\Component\Serializer\Definition\TransformerInterface;

class DatetimeTransformer implements TransformerInterface
{
    /** @var string */
    protected $format;

    /**
     * @param string $format
     */
    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function normalize($value)
    {
        if (!$value instanceof \DateTime) {
            return null;
        }

        return $value->format($this->format);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function denormalize($value)
    {
        if ($value === null) {
            return $value;
        }

        return \DateTime::createFromFormat($this->format, $value);
    }
}

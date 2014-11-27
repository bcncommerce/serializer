<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition\Transformer;

use Bcn\Component\Serializer\Definition\TransformerInterface;

class NumberTransformer implements TransformerInterface
{
    /** @var int */
    protected $decimals;

    /** @var string */
    protected $decimalPoint;

    /** @var string */
    protected $thousandSeparator;

    /**
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     */
    public function __construct($decimals = 4, $decimalPoint = '.', $thousandSeparator = '')
    {
        $this->decimals          = $decimals;
        $this->decimalPoint      = $decimalPoint;
        $this->thousandSeparator = $thousandSeparator;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function normalize($value)
    {
        $value = number_format($value, $this->decimals, $this->decimalPoint, $this->thousandSeparator);

        if ($this->decimalPoint == '.' && $this->thousandSeparator == '') {
            $value = ($this->decimals == 0) ? intval($value) : floatval($value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function denormalize($value)
    {
        $value = str_replace($this->thousandSeparator, '', $value);
        $value = str_replace($this->decimalPoint, '.', $value);

        return ($this->decimals == 0) ? intval($value) : floatval($value);
    }
}

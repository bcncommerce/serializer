<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

class NumberNormalizer implements NormalizerInterface
{
    /** @var integer */
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
     * @param  mixed $object
     * @return mixed
     */
    public function normalize($object)
    {
        $object = str_replace($this->thousandSeparator, '',  $object);
        $object = str_replace($this->decimalPoint,      '.', $object);
        $object = $this->decimals == 0 ? intval($object) : floatval($object);

        return $object;
    }

    /**
     * @param  mixed $data
     * @param  mixed $object
     * @return mixed
     */
    public function denormalize($data, &$object = null)
    {
        $object = number_format($data, $this->decimals, $this->decimalPoint, $this->thousandSeparator);

        return $object;
    }
}

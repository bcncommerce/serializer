<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

class DatetimeNormalizer implements NormalizerInterface
{
    /** @var string */
    protected $format;

    /**
     * @param string $format
     */
    public function __construct($format = 'Y-m-d\TH:i:sO')
    {
        $this->format = $format;
    }

    /**
     * @param  mixed $object
     * @return mixed
     */
    public function normalize($object)
    {
        if (!$object instanceof \DateTime) {
            throw new \InvalidArgumentException('Normalizable object should be instance of DateTime class');
        }

        return (string) $object->format($this->format);
    }

    /**
     * @param  mixed           $data
     * @param  mixed           $object
     * @return \DateTime|mixed
     */
    public function denormalize($data, &$object = null)
    {
        $object = \DateTime::createFromFormat($this->format, $data);

        return $object;
    }
}

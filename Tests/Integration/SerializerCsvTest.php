<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Encoder\Csv\CsvEncoder;
use Bcn\Component\Serializer\Encoder\EncoderDecoderInterface;
use Bcn\Component\Serializer\Serializer;

class SerializerCsvTest extends SerializerTestCase
{
    /**
     * @return EncoderDecoderInterface
     */
    protected function getEncoder()
    {
        return new CsvEncoder(';');
    }

    /**
     * @return string
     */
    protected function getFileExtension()
    {
        return 'csv';
    }

    /**
     * @return array
     */
    protected function getSupportedTypes()
    {
        return array('document_array');
    }
}

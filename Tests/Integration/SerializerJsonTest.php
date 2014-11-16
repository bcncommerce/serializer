<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Encoder\EncoderDecoderInterface;
use Bcn\Component\Serializer\Encoder\Json\JsonEncoder;
use Bcn\Component\Serializer\Serializer;

class SerializerJsonTest extends SerializerTestCase
{
    /**
     * @return EncoderDecoderInterface
     */
    protected function getEncoder()
    {
        return new JsonEncoder(JSON_PRETTY_PRINT);
    }

    /**
     * @return string
     */
    protected function getFileExtension()
    {
        return 'json';
    }

    /**
     * @return array
     */
    protected function getSupportedTypes()
    {
        return array('document', 'document_nested', 'document_array');
    }
}

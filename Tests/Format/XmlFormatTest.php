<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Format;

use Bcn\Component\Serializer\Format\XmlFormat;
use Bcn\Component\Serializer\Tests\TestCase;

class XmlFormatTest extends TestCase
{
    /**
     *
     */
    public function testGetNames()
    {
        $format = new XmlFormat();

        $names = $format->getNames();

        $this->assertContains('xml', $names);
        $this->assertContains('application/xml', $names);
        $this->assertContains('text/xml', $names);
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Csv;

use Bcn\Component\Serializer\Encoder\Csv\CsvIterator;
use Bcn\Component\Serializer\Tests\TestCase;

class CsvIteratorTest extends TestCase
{
    /**
     * @param string $csv
     * @param array  $iterations
     * @dataProvider provideIterations
     */
    public function testIterate($csv, $iterations)
    {
        $stream = fopen($csv, 'r');
        $data = array();

        $iterator = new CsvIterator($stream, ';');
        foreach ($iterator as $line => $row) {
            $data[$line] = $row;
        }

        $this->assertEquals($iterations, $data);
    }

    /**
     * @param string $csv
     * @param array  $iterations
     * @dataProvider provideIterations
     */
    public function testIterateMultipleTimes($csv, $iterations)
    {
        $stream = fopen($csv, 'r');
        $data = array();

        $iterator = new CsvIterator($stream, ';');
        foreach ($iterator as $line => $row) {
        }

        foreach ($iterator as $line => $row) {
            $data[$line] = $row;
        }

        $this->assertEquals($iterations, $data);
    }

    public function provideIterations()
    {
        return array(
            'Sample CSV' => array(
                __DIR__.'/files/sample.csv',
                json_decode(file_get_contents(__DIR__.'/files/sample.json'), true),
            ),
            'Empty CSV' => array(
                __DIR__.'/files/empty.csv',
                json_decode(file_get_contents(__DIR__.'/files/empty.json'), true),
            ),
            'Empty CSV with extra new lines' => array(
                __DIR__.'/files/empty-with-newlines.csv',
                json_decode(file_get_contents(__DIR__.'/files/empty-with-newlines.json'), true),
            ),
        );
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Tests\Integration\Document;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const DOCUMENT_CLASS = 'Bcn\Component\Serializer\Tests\Integration\Document';

    /** @var array */
    protected $streams = array();

    /**
     * @param  string $suffix
     * @return array
     */
    protected function getDocumentData($suffix = '')
    {
        $data = array(
            'name'        => 'Test name '.$suffix,
            'description' => 'Test description '.$suffix,
            'rank'        => 11,
            'rating'      => 93.31,
        );

        return $data;
    }

    /**
     * @return array
     */
    protected function getNestedDocumentData()
    {
        $data = $this->getDocumentData();
        $data['parent'] = $this->getDocumentData('parent');
        $data['parent']['parent'] = null;

        return $data;
    }

    /**
     * @param  string   $suffix
     * @return Document
     */
    protected function getDocument($suffix = '')
    {
        $document = new Document();
        $document->setName('Test name '.$suffix);
        $document->setDescription('Test description '.$suffix);
        $document->setRank(11);
        $document->setRating(93.31);

        return $document;
    }

    /**
     * @return Document
     */
    protected function getNestedDocument()
    {
        $document = $this->getDocument();
        $document->setParent($this->getDocument("parent"));

        return $document;
    }

    /**
     * @return array
     */
    protected function getDocumentsData()
    {
        return array($this->getDocumentData('one'), $this->getDocumentData('two'));
    }

    /**
     * @return Document[]
     */
    protected function getDocuments()
    {
        return array($this->getDocument('one'), $this->getDocument('two'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Serializer
     */
    public function getSerializerMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Serializer')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Encoder\EncoderInterface
     */
    public function getEncoderMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Encoder\EncoderInterface')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Decoder\DecoderInterface
     */
    public function getDecoderMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Decoder\DecoderInterface')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected function getAccessorMock()
    {
        return $this->getMockBuilder('Symfony\Component\PropertyAccess\PropertyAccessorInterface')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Type\TypeInterface
     */
    public function getTypeMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Type\TypeInterface')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\SerializerFactory
     */
    public function getSerializerFactoryMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\SerializerFactory')
            ->getMock();
    }

    /**
     * @param  string $file
     * @return string
     */
    protected function getFixtureUri($file)
    {
        $reflection = new \ReflectionClass($this);

        return dirname($reflection->getFileName()).DIRECTORY_SEPARATOR.$file;
    }

    /**
     * @param  string $file
     * @return string
     */
    public function getFixtureContent($file)
    {
        return file_get_contents($this->getFixtureUri($file));
    }

    /**
     * @param  string   $file
     * @param  string   $mode
     * @return resource
     */
    public function getFixtureStream($file, $mode = 'r')
    {
        $stream = fopen($this->getFixtureUri($file), $mode);
        $this->streams[] = $stream;

        return $stream;
    }

    /**
     * @param  string   $data
     * @return resource
     */
    public function getDataStream($data = null)
    {
        $stream = fopen("php://temp", "rw+");
        if ($data !== null) {
            fputs($stream, $data);
            rewind($stream);
        }

        $this->streams[] = $stream;

        return $stream;
    }

    /**
     *
     */
    protected function tearDown()
    {
        foreach ($this->streams as $stream) {
            fclose($stream);
        }

        $this->streams = array();
    }
}

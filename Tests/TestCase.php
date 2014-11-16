<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Normalizer;
use Bcn\Component\Serializer\Tests\Integration\Document;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const DOCUMENT_CLASS = 'Bcn\Component\Serializer\Tests\Integration\Document';

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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getNormalizerMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Normalizer')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getTypeMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Type\TypeInterface')
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getTypeFactoryMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Type\TypeFactory')
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getCodecMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Encoder\CodecInterface')
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAccessorMock()
    {
        return $this->getMockBuilder('Symfony\Component\PropertyAccess\PropertyAccessorInterface')
            ->getMock();
    }

    /**
     * @param $file
     * @return string
     */
    protected function getFixturePath($file)
    {
        $reflection = new \ReflectionClass($this);

        return dirname($reflection->getFileName()).DIRECTORY_SEPARATOR.$file;
    }

    /**
     * @param $file
     * @return string
     */
    public function getFixtureContent($file)
    {
        return file_get_contents($this->getFixturePath($file));
    }

    /**
     * @param $expected
     * @param $actual
     * @param string $message
     */
    public static function assertAvailableOptions($expected, $actual, $message = '')
    {
        self::assertEquals(sort($expected), sort($actual), $message);
    }
}

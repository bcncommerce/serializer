<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Normalizer;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const DOCUMENT_CLASS = 'Bcn\Component\Serializer\Tests\Document';

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
     * @param  string $suffix
     * @return string
     */
    protected function getDocumentEncoded($suffix = '')
    {
        return json_encode($this->getDocumentData($suffix));
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
     * @return string
     */
    protected function getNestedDocumentEncoded()
    {
        return json_encode($this->getNestedDocumentData());
    }

    /**
     * @param  string   $suffix
     * @return Document
     */
    protected function getDocumentObject($suffix = '')
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
    protected function getNestedDocumentObject()
    {
        $document = $this->getDocumentObject();
        $document->setParent($this->getDocumentObject("parent"));

        return $document;
    }

    /**
     * @return array
     */
    protected function getDocumentDataCollection()
    {
        return array('one' => $this->getDocumentData('one'), 'two' => $this->getDocumentData('two'));
    }

    /**
     * @return Document[]
     */
    protected function getDocumentObjectCollection()
    {
        return array('one' => $this->getDocumentObject('one'), 'two' => $this->getDocumentObject('two'));
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
    public function getEncoderDecoderMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Encoder\EncoderDecoderInterface')
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
     * @param $expected
     * @param $actual
     * @param string $message
     */
    public static function assertAvailableOptions($expected, $actual, $message = '')
    {
        self::assertEquals(sort($expected), sort($actual), $message);
    }
}

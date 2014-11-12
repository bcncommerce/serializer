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
            'name' => 'Test name '.$suffix,
            'description' => 'Test description '.$suffix,
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
    protected function getDocumentObject($suffix = '')
    {
        $document = new Document();
        $document->setName('Test name '.$suffix);
        $document->setDescription('Test description '.$suffix);

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
    public function getFactoryMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Type\TypeFactory')
                ->getMock();
    }
}

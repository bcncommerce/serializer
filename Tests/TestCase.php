<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const DOCUMENT_CLASS = 'Bcn\Component\Serializer\Tests\Document';

    /** @var array */
    protected $streams = array();

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
            'parent'      => null,
        );

        return $data;
    }

    /**
     * @return array
     */
    public function getDocumentFormats()
    {
        return array(
            'json' => array('json', $this->getFixtureContent('resources/document.json')),
            'xml'  => array('xml',  $this->getFixtureContent('resources/document.xml')),
        );
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
    protected function getNestedDocumentData()
    {
        $data = $this->getDocumentData();
        $data['parent'] = $this->getDocumentData('parent');

        return $data;
    }

    /**
     * @return array
     */
    public function getNestedDocumentFormats()
    {
        return array(
            'json' => array('json', $this->getFixtureContent('resources/document_nested.json')),
            'xml'  => array('xml',  $this->getFixtureContent('resources/document_nested.xml')),
        );
    }

    /**
     * @return Document[]
     */
    protected function getDocuments()
    {
        return array($this->getDocument('one'), $this->getDocument('two'));
    }

    /**
     * @return array
     */
    protected function getDocumentsData()
    {
        return array($this->getDocumentData('one'), $this->getDocumentData('two'));
    }

    /**
     * @return array
     */
    public function getDocumentArrayFormats()
    {
        return array(
            'json' => array('json', $this->getFixtureContent('resources/document_array.json')),
            'xml'  => array('xml',  $this->getFixtureContent('resources/document_array.xml')),
        );
    }

    public function getAttributes()
    {
        return array(
            array('code' => 'name',        'value' => 'Foo'),
            array('code' => 'description', 'value' => 'Baz'),
            array('code' => 'rating',      'value' => 100),
        );
    }

    /**
     * @return array
     */
    public function getAttributesFormats()
    {
        return array(
            'json' => array('json', $this->getFixtureContent('resources/attributes.json')),
            'xml'  => array('xml',  $this->getFixtureContent('resources/attributes.xml')),
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Format\Parser\Handler\HandlerInterface
     */
    public function getParserHandlerMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Format\Parser\Handler\HandlerInterface')
                ->disableOriginalConstructor()
                ->getMock();
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Format\FormatInterface
     */
    public function getFormatMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Format\FormatInterface')
                ->disableOriginalConstructor()
                ->getMock();
    }
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Definition\Builder
     */
    public function getBuilderMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Definition\Builder')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Resolver
     */
    public function getResolverMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Resolver')
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Definition\TransformerInterface
     */
    public function getTransformerMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Definition\TransformerInterface')
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Definition
     */
    public function getDefinitionMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Definition')
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Bcn\Component\Serializer\Normalizer
     */
    public function getNormalizerMock()
    {
        return $this->getMockBuilder('Bcn\Component\Serializer\Normalizer')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param  string $file
     * @return string
     */
    protected function getFixtureUri($file)
    {
        if ($file && $file[0] == "/") {
            return $file;
        }

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
     * @param  resource $stream
     * @return string
     */
    public function getStreamContent($stream)
    {
        rewind($stream);

        return stream_get_contents($stream);
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

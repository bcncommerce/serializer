<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Writer;

use Bcn\Component\Serializer\Definition;

class XmlWriter
{
    /**
     * @param resource   $stream
     * @param mixed      $origin
     * @param Definition $definition
     */
    public function write($stream, $origin, Definition $definition)
    {
        $writer = new \XMLWriter();

        $this->open($writer, $stream);
        $this->initialize($writer, $definition);
        $this->writeNode($writer, $stream, $origin, $definition);
        $this->finalize($writer);
        $this->flush($writer, $stream);

        unset($writer);
    }

    /**
     * @param \XMLWriter $writer
     * @param resource   $stream
     */
    protected function open(\XMLWriter $writer, $stream)
    {
        if (is_resource($stream)) {
            $writer->openMemory();
        } else {
            $writer->openUri($stream);
        }
    }

    /**
     * @param Definition $definition
     * @param \XMLWriter $writer
     */
    protected function initialize($writer, Definition $definition)
    {
        $writer->setIndent(true);
        $writer->setIndentString("    ");
        $writer->startDocument("1.0", "UTF-8");
        $writer->startElement($definition->getNodeName() ?: "root");
    }

    /**
     * @param \XMLWriter $writer
     * @param resource   $stream
     * @param mixed      $origin
     * @param Definition $definition
     */
    protected function writeNode(\XMLWriter $writer, $stream, $origin, Definition $definition)
    {
        if ($definition->isArray()) {
            $this->writeArray($writer, $stream, $origin, $definition);
        }

        if ($definition->isObject()) {
            $this->writeObject($writer, $stream, $origin, $definition);
        }

        if ($definition->isScalar()) {
            $this->writeScalar($writer, $origin, $definition);
        }

        $this->flush($writer, $stream);
    }

    /**
     * @param  \XMLWriter $writer
     * @param  resource   $stream
     * @param  mixed      $origin
     * @param  Definition $definition
     * @return mixed
     */
    protected function writeArray(\XMLWriter $writer, $stream, $origin, Definition $definition)
    {
        $prototype = $definition->getPrototype();
        $collection = $definition->extract($origin);

        foreach ($collection as $index => $entry) {
            $writer->startElement($prototype->getNodeName());
            if ($definition->getKeyName()) {
                $writer->writeAttribute($definition->getKeyName(), $index);
            }

            $this->writeNode($writer, $stream, $entry, $prototype);
            $writer->endElement();
        }

        return $origin;
    }

    /**
     * @param \XMLWriter $writer
     * @param resource   $stream
     * @param mixed      $origin
     * @param Definition $definition
     */
    protected function writeObject(\XMLWriter $writer, $stream, $origin, Definition $definition)
    {
        $object = $definition->extract($origin);

        if ($object === null) {
            return;
        }

        foreach ($definition->getProperties() as $propertyName => $propertyDefinition) {
            $writer->startElement($propertyDefinition->getNodeName());
            $this->writeNode($writer, $stream, $object, $propertyDefinition);
            $writer->endElement();
        }
    }

    /**
     * @param \XMLWriter $writer
     * @param mixed      $origin
     * @param Definition $definition
     */
    protected function writeScalar(\XMLWriter $writer, $origin, Definition $definition)
    {
        $content = $definition->extract($origin);
        if (is_numeric($content)) {
            $writer->writeRaw($content);
        } else {
            $writer->writeCdata($content);
        }
    }

    /**
     * @param \XMLWriter $writer
     */
    protected function finalize($writer)
    {
        $writer->endElement();
        $writer->endDocument();
    }

    /**
     * @param \XMLWriter $writer
     * @param resource   $stream
     */
    protected function flush(\XMLWriter $writer, $stream)
    {
        if (is_resource($stream)) {
            fwrite($stream, $writer->outputMemory(true));
        } else {
            $writer->flush(true);
        }
    }
}

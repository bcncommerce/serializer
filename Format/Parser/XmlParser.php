<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser;

use Bcn\Component\Serializer\Format\Parser\Handler\HandlerInterface;

class XmlParser
{
    /**
     * @param  resource         $stream
     * @param  HandlerInterface $handler
     * @throws \Exception
     */
    public function parse($stream, HandlerInterface $handler)
    {
        $isStream = is_resource($stream);
        if (!$isStream) {
            $stream = fopen($stream, "r");
        }

        $parser = $this->create();
        $this->assign($parser, $handler);
        $this->read($stream, $parser);
        $this->free($parser);

        if (!$isStream) {
            fclose($stream);
        }
    }

    /**
     * @return resource
     */
    protected function create()
    {
        $parser = xml_parser_create("UTF-8");

        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

        return $parser;
    }

    /**
     * @param resource         $parser
     * @param HandlerInterface $handler
     */
    protected function assign($parser, HandlerInterface $handler)
    {
        xml_set_element_handler(
            $parser,
            function ($parser, $name, $attributes) use ($handler) {
                $handler->start($name, $attributes);
            },
            function ($parser, $name) use ($handler) {
                $handler->end($name);
            }
        );

        xml_set_character_data_handler(
            $parser,
            function ($parser, $data) use ($handler) {
                $handler->append($data);
            }
        );
    }

    /**
     * @param $stream
     * @param $parser
     * @throws \Exception
     */
    protected function read($stream, $parser)
    {
        while ($data = fread($stream, 4096)) {
            if (xml_parse($parser, $data, feof($stream))) {
                continue;
            }

            throw $this->getParserErrorException($parser);
        }
    }
    /**
     * @param $parser
     * @return \Exception
     */
    protected function getParserErrorException($parser)
    {
        return new \Exception(sprintf(
            "XML error: %s at line %d",
            xml_error_string(xml_get_error_code($parser)),
            xml_get_current_line_number($parser)
        ));
    }

    /**
     * @param $parser
     */
    protected function free($parser)
    {
        xml_parser_free($parser);
    }
}

<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Exception;

class MalformedXmlException extends \Exception
{
    /**
     * @param \LibXMLError[] $errors
     * @param int            $code
     * @param \Exception     $previous
     */
    public function __construct($errors = array(), $code = 0, \Exception $previous = null)
    {
        parent::__construct($this->formatAll($errors), $code, $previous);
    }

    /**
     * @param  \LibXMLError[] $errors
     * @return string
     */
    protected function formatAll($errors)
    {
        $messages = "";
        foreach ($errors as $error) {
            $messages .= "\n".$this->format($error);
        }

        return ltrim($messages);
    }

    /**
     * @param  \LibXMLError $error
     * @return string
     */
    protected function format(\LibXMLError $error)
    {
        return sprintf(
            "%s at %s:%d:%d",
            trim($error->message),
            $error->file,
            $error->line,
            $error->column
        );
    }
}

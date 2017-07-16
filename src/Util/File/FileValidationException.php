<?php
namespace Util\File;

/**
 * Class FileValidationException
 * @package Util\File
 */
class FileValidationException extends \RuntimeException
{
    /**
     * FileValidationException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

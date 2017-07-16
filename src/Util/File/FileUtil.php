<?php
namespace Util\File;

/**
 * Class FileUtil
 * @package Util\File
 */
class FileUtil
{
    /**
     * Sanitizes a path to prevent Path Traversal attack.
     *
     * @param string $allowedDirectory Directory in which the file should be present
     * @param string $dirtyFilePath Path to the file
     * @return string Full Path to the file
     * @throws FileValidationException If the file is not found in the allowed directory.
     */
    public static function sanitizePath(string $allowedDirectory, string $dirtyFilePath): string
    {
        $allowedDirectory = realpath($allowedDirectory);
        $realFilePath = realpath($allowedDirectory . DIRECTORY_SEPARATOR . $dirtyFilePath);

        if (false === $realFilePath || 0 !== strpos($realFilePath, $allowedDirectory)) {
            throw new FileValidationException('File not found');
        }

        return $realFilePath;
    }
}

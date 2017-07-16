<?php
namespace Util\File;

/**
 * FileReaderInterface
 * @package Util\File
 */
interface FileReaderInterface
{
    /**
     * Reads the lines of a file from $offset.
     *
     * @param int $offset Positive integer that represents the position of the first line that should be read
     * @param int $limit Positive integer that represents the maximum number of lines that should be read
     * @return TextLine[] Returns the read lines as an array of TextLine that contains the line number and the content
     * @throws \InvalidArgumentException In case the offset or limit is less than 0
     */
    public function readLines(int $offset, int $limit): array;
}

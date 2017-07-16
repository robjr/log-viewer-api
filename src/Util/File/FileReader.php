<?php
namespace Util\File;

/**
 * This class will read a file from the beginning of the file.
 *
 * It will only read until the desired lines are found as there is no need to see the end of the file.
 * The desired lines are expressed by the offset and limit.
 * Worst Case Complexity is O(n + k) where n is the offset and k the limit.
 *
 * It only keeps in memory the desired lines and a buffer with the line being read.
 *
 * @package Util\File
 */
class FileReader implements FileReaderInterface
{
    /**
     * @var resource
     */
    protected $file;

    /**
     * FileReader constructor.
     * @param string $fileName
     * @throws \RuntimeException
     */
    public function __construct(string $fileName)
    {
        $this->file = fopen($fileName, 'r');

        if (!$this->file) {
            throw new \RuntimeException("File $fileName could not be opened");
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param int $offset {@inheritdoc}
     * @param int $limit {@inheritdoc}
     * @return TextLine[] {@inheritdoc}
     */
    public function readLines(int $offset, int $limit): array
    {
        $this->assertLimit($limit);
        $this->assertOffset($offset);

        $lines = [];
        $totalLines = 0;

        $this->reset();

        while ($limit && $line = fgets($this->file)) {
            if ($totalLines++ >= $offset) {
                $lines[] = new TextLine($totalLines, rtrim($line));
                $limit--;
            }
        }

        if (empty($lines)) {
            throw new \InvalidArgumentException('Offset position does not exist');
        }

        return $lines;
    }

    /**
     * Asserts offset valid.
     *
     * @param int $offset
     */
    protected function assertOffset(int $offset)
    {
        if ($offset < 0) {
            throw new \InvalidArgumentException('Offset should be greater than 0');
        }
    }

    /**
     * Asserts limit valid.
     *
     * @param int $limit
     */
    protected function assertLimit(int $limit)
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException('Limit should be greater than 0');
        }
    }

    /**
     * Resets file pointer.
     */
    protected function reset()
    {
        rewind($this->file);
    }
}

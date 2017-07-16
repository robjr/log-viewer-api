<?php
namespace Util\File;

/**
 * This class will read a file considering that the offset 0 is the last line of the file.
 *
 * It has a worst case complexity of O(x + n + k) where x is the total number of lines that the file has, n is the
 * offset and k the limit.
 *
 * It runs the file once O(n) to get the total lines number and calculate a new offset that considers 0 as the
 * first line of the file, and a second to get the desired lines O(n + k). This strategy demonstrated to be more
 * efficient for larger files than truly reading the file from backwards and going until the end of the file O(n)
 * to calculate the line number of the last position of the file. This was done using fseek and fgetc, but it was
 * extremely expensive. Further investigation should be done to see how these functions are implemented in C and also
 * to check if a compiled version would be more efficient than running through PHPs interpreter.
 *
 * This reader uses fgets to read the content of a line. Fgets uses php_stream_get_line and php_stream_locate_eol to get
 * the content of a line and only keeps in memory a buffer for that line.
 *
 * SplFileObject was not used because empirical investigation showed it to be much more expensive than using native
 * functions to read a file.
 *
 * @see https://github.com/php/php-src/blob/d55b43d9cb7ba2e96670841b9ab90b92c822eb3f/main/streams/streams.c
 * @see https://github.com/php/php-src/blob/a79ec404b21367ba89e8b3fda157c01d8d60edbf/ext/spl/internal/splfileobject.inc
 *
 * @package Util\File
 */
class BackwardReader extends FileReader implements FileReaderInterface
{
    /**
     * BackwardReader constructor.
     * @param string $fileName
     * @throws \RuntimeException
     */
    public function __construct(string $fileName)
    {
        parent::__construct($fileName);
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

        $this->reset();

        $total = $this->countLines();
        $realOffset = $total - $limit - $offset;

        if ($realOffset < 0) {
            $realOffset = 0;
            $limit = $total - $offset;
        }

        return parent::readLines($realOffset, $limit);
    }

    /**
     * Counts the number of lines that the file has from its pointer.
     *
     * @return int Number of lines that the file has from its pointer
     */
    private function countLines(): int
    {
        $total = 0;
        while (fgets($this->file)) {
            $total++;
        }

        return $total;
    }
}

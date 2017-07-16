<?php
namespace Service;

use Util\File\FileReaderInterface;
use Util\File\TextLine;

class LogService
{
    /**
     * @var FileReaderInterface
     */
    private $fileReader;

    /**
     * LogService constructor.
     * @param FileReaderInterface $fileReader
     */
    public function __construct(FileReaderInterface $fileReader)
    {
        $this->fileReader = $fileReader;
    }

    /**
     * Gets a set of lines from the reader.
     *
     * @param int $blockNumber Position of the block
     * @param int $blockSize Size of the block
     * @return TextLine[]
     */
    public function readBlock(int $blockNumber, int $blockSize): array
    {
        return $this->fileReader->readLines($blockSize * $blockNumber, $blockSize);
    }
}

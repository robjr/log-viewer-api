<?php
namespace Test;

use Util\File\FileReader;
use Util\File\FileReaderInterface;

class FileReaderTest extends BaseReaderTest
{
    public function buildReader(string $content): FileReaderInterface
    {
        return new FileReader($this->createTempFile($content));
    }

    public function testLineByLine()
    {
        $reader = $this->buildReader($this->generateFileContent(10));

        for ($i = 0; $i < 10; $i++) {
            $line = $reader->readLines($i, 1);
            $this->assertEquals('Line ' . ($i + 1), $line[0]->getContent());
        }
    }

    public function testOffsetAndLimit()
    {
        $reader = $this->buildReader($this->generateFileContent(10));

        $lines = $reader->readLines(0, 2);
        $this->assertEquals('Line 1', $lines[0]->getContent());
        $this->assertEquals('Line 2', $lines[1]->getContent());

        $lines = $reader->readLines(8, 2);
        $this->assertEquals('Line 9', $lines[0]->getContent());
        $this->assertEquals('Line 10', $lines[1]->getContent());
    }
}

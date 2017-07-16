<?php
namespace Test;

use Util\File\BackwardReader;
use Util\File\FileReaderInterface;

class BackwardReaderTest extends BaseReaderTest
{
    public function buildReader(string $content): FileReaderInterface
    {
        return new BackwardReader($this->createTempFile($content));
    }

    public function testsLineByLine()
    {
        $reader = $this->buildReader($this->generateFileContent(10));

        for ($i = 9; $i >= 0; $i--) {
            $line = $reader->readLines($i, 1);
            $lineNumber = 10 - $i;
            $this->assertEquals("Line {$lineNumber}", $line[0]->getContent());
        }
    }

    public function testOffsetAndLimit()
    {
        $reader = $this->buildReader($this->generateFileContent(10));

        $lines = $reader->readLines(8, 2);
        $this->assertEquals('Line 1', $lines[0]->getContent());
        $this->assertEquals('Line 2', $lines[1]->getContent());

        $lines = $reader->readLines(0, 2);
        $this->assertEquals('Line 9', $lines[0]->getContent());
        $this->assertEquals('Line 10', $lines[1]->getContent());
    }
}

<?php
namespace Test;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Util\File\FileReaderInterface;

abstract class BaseReaderTest extends TestCase
{
    protected $directory;

    abstract public function buildReader(string $content): FileReaderInterface;

    public function setUp()
    {
        $this->directory = vfsStream::setup('var/tmp');
    }

    public function createTempFile(string $content): string
    {
        file_put_contents(vfsStream::url('var/tmp/log.txt'), $content);

        return vfsStream::url('var/tmp/log.txt');
    }

    public function testInvalidOffset()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = $this->buildReader('Line 1');
        $reader->readLines(-1, 1);
    }

    public function testInvalidLimit()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = $this->buildReader('Line 1');
        $reader->readLines(1, -1);
    }

    public function testFileWithOneLineOnly()
    {
        $reader = $this->buildReader('Line 1');
        $line = $reader->readLines(0, 5);

        $this->assertEquals('Line 1', $line[0]->getContent());
    }

    public function testLinesWithoutContent()
    {
        $reader = $this->buildReader(PHP_EOL . PHP_EOL);
        $lines = $reader->readLines(0, 2);

        $this->assertCount(2, $lines);
        $this->assertEmpty($lines[0]->getContent());
        $this->assertEmpty($lines[1]->getContent());
    }

    public function testLimitGreaterThanFileSize()
    {
        $reader = $this->buildReader($this->generateFileContent(10));
        $lines = $reader->readLines(9, 100);

        $this->assertCount(1, $lines);
    }

    /**
     * Generates content in which each line has the format: Line X, where X is the line number.
     *
     * @param int $totalLines
     * @return string
     */
    public function generateFileContent(int $totalLines): string
    {
        $content = '';

        for ($i = 1; $i <= $totalLines; $i++) {
            $content .= "Line {$i}" . PHP_EOL;
        }

        return $content;
    }
}

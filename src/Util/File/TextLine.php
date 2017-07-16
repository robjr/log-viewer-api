<?php
namespace Util\File;

/**
 * Class TextLine
 * @package Util\File
 */
class TextLine implements \JsonSerializable
{
    /**
     * @var int
     */
    private $order;

    /**
     * @var string
     */
    private $content;

    /**
     * FileLine constructor.
     * @param int $order
     * @param string $content
     */
    public function __construct(int $order, string $content)
    {
        $this->order = $order;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    public function jsonSerialize(): array
    {
        return ['order' => $this->order, 'content' => $this->content];
    }
}

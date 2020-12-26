<?php

class Tag implements JsonSerializable
{
    private ?int $tagId;
    private string $tag;

    public function __construct(string $tag, int $tagId = null)
    {
        $this->tagId = $tagId;
        $this->tag = $tag;
    }

    public function jsonSerialize(): array
    {
        return [
            'tagId' => $this->tagId,
            'tag' => $this->tag
        ];
    }

    /**
     * @return int|null
     */
    public function getTagId(): ?int
    {
        return $this->tagId;
    }

    /**
     * @param int|null $tagId
     */
    public function setTagId(?int $tagId): void
    {
        $this->tagId = $tagId;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }


}
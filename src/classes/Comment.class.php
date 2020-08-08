<?php

class Comment implements JsonSerializable
{
    private $userId;
    private $itemId;
    private $comment;
    private $created;

    public function __construct($itemId, $userId, $comment)
    {
        $this->itemId = $itemId;
        $this->userId = $userId;
        $this->comment = $comment;
        $this->created = date('Y-m-d H:i:s');
    }

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'itemId' => $this->itemId,
            'comment' => $this->comment,
            'created' => $this->created
        ];
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param int $itemId
     */
    public function setItemId(int $itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(string $created)
    {
        $this->created = $created;
    }
}
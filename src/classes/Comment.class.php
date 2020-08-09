<?php

class Comment implements JsonSerializable
{
    private int $userId;
    private int $itemId;
    private string $comment;
    private string $created;

    public function __construct(int $itemId, int $userId, string $comment, string $date = null)
    {
        $this->setItemId($itemId);
        $this->setUserId($userId);
        $this->setComment($comment);
        $this->setCreated($date);
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
        try {
            $stringValidator = new StringValidator();
            $this->comment = $stringValidator->validate($comment);
        } catch (Exception $e) {

        }
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string|null $created
     */
    public function setCreated($created)
    {
        $this->created = $created ?? date('Y-m-d H:i:s');
    }
}
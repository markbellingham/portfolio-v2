<?php

class Favourite implements JsonSerializable
{
    private int $userId;
    private int $itemId;

    public function __construct($userId, $itemId)
    {
        $this->setUserId($userId);
        $this->setItemId($itemId);
    }

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'itemId' => $this->itemId
        ];
    }

    /**
     * @return int
     */
    public function getUserId(): int
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
    public function getItemId(): int
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
}
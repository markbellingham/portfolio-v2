<?php

class Favourite implements JsonSerializable
{
    private $userId;
    private $photoId;

    public function __construct($userId, $photoId)
    {
        $this->userId = $userId;
        $this->photoId = $photoId;
    }

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'photoId' => $this->photoId
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
    public function getPhotoId(): int
    {
        return $this->photoId;
    }

    /**
     * @param int $photoId
     */
    public function setPhotoId(int $photoId)
    {
        $this->photoId = $photoId;
    }
}
<?php

class Comment implements JsonSerializable
{
    private $userId;
    private $photoId;
    private $comment;
    private $created;

    public function __construct($photoId, $username, $comment)
    {
        $this->photoId = $photoId;
        $people = new People();
        $user = $people->findUserByValue('name', $username);
        $this->userId = $user->getId();
        $this->comment = $comment;
        $this->created = date('Y-m-d H:i:s');
    }

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'photoId' => $this->photoId,
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
    public function getPhotoId()
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
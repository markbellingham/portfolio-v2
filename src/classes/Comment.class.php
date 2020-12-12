<?php

class Comment extends Exception implements JsonSerializable
{
    private int $userId;
    private int $itemId;
    private string $comment;
    private string $created;
    private bool $checked = false;
    private array $errors = [];

    /**
     * Comment constructor.
     * @param int $itemId
     * @param int $userId
     * @param string $comment
     * @param string|null $date
     * @throws Exception
     */
    public function __construct(int $itemId, int $userId, string $comment, string $date = null)
    {
        parent::__construct();
        $this->setItemId($itemId);
        $this->setUserId($userId);
        $this->setComment($comment);
        $this->setCreated($date);
        if(count($this->errors) > 0) {
            throw new Exception('Comment invalid');
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
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

    /**
     * @return string
     */
    public function getComment(): string
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
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string|null $created
     */
    public function setCreated(?string $created)
    {
        $this->created = $created ?? date('Y-m-d H:i:s');
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     */
    public function setChecked(bool $checked): void
    {
        $this->checked = $checked;
    }


}
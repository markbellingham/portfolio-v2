<?php

class User implements JsonSerializable
{
    private ?int $id = null;
    private string $username;
    private string $uuid;
    private StringValidator $stringValidator;
    private array $errors = [];

    /**
     * User constructor.
     * @param string $username
     * @param string $uuid
     * @param int|null $id
     * @throws Exception
     */
    public function __construct(string $username, string $uuid, ?int $id = null)
    {
        $this->stringValidator = new StringValidator();
        $this->setUsername($username);
        $this->setUuid($uuid);
        $this->setId($id);
        if(count($this->errors) > 0) {
            throw new Exception('Invalid User');
        }
    }

    /**
     * To enable json_encode() to output private/protected properties
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'uuid' => $this->uuid
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        try {
            $this->username = $this->stringValidator->validate($username);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void
    {
        try {
            $this->uuid = $this->stringValidator->validate($uuid);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }
}
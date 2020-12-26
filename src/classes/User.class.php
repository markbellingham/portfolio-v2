<?php

class User implements JsonSerializable
{
    private ?int $id = null;
    private string $name;
    private string $uuid;
    private int $admin;
    private StringValidator $stringValidator;
    private array $errors = [];

    /**
     * User constructor.
     * @param string $name
     * @param string $uuid
     * @param int|null $id
     * @throws Exception
     */
    public function __construct(string $name = '', string $uuid = '', ?int $id = null)
    {
        $this->stringValidator = new StringValidator();
        $this->setName($name);
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
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'uuid' => $this->uuid
        ];
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $username
     */
    public function setName($username): void
    {
        try {
            $this->name = $this->stringValidator->validate($username);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getUuid(): string
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

    /**
     * @return int
     */
    public function getAdmin(): int
    {
        return $this->admin;
    }

    /**
     * @param int $admin
     */
    public function setAdmin(int $admin = 0): void
    {
        $this->admin = $admin;
    }


}
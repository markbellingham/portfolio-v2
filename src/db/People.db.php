<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class People
{
    protected $db;
    protected $data;

    /**
     * People constructor.
     */
    public function __construct()
    {
        $this->db = MyPDO::instance('People');
    }

    /**
     * @return User[]
     */
    public function findAllUsers()
    {
        $sql = "SELECT id, name, uuid FROM users";
        return $this->db->run($sql)->fetchAll();
    }

    /**
     * @param string $column
     * @param string $value
     * @return bool|object
     */
    public function findUserByValue(string $column, string $value)
    {
        if( !in_array( $column, ['id','name','uuid'] ) ) {
            return false;
        }
        $params = [$value];
        $sql = "SELECT id, name, uuid FROM users WHERE $column = ?";
        return $this->db->run($sql, $params)->fetch();
    }

    /**
     * @param $user
     * @return User
     */
    public function saveUser(User $user)
    {
        $params = [$user->getUsername(), $user->getUuid()];
        $sql = "INSERT INTO users (name, uuid) VALUES (?, ?)";
        $this->db->run($sql, $params);
        if(!$this->db->errors()) {
            $user->setId($this->getLastInsertId());
        }
        return $user;
    }

    /**
     * @return int
     */
    private function getLastInsertId()
    {
        $sql = "SELECT LAST_INSERT_ID() AS last_id";
        $result = $this->db->run($sql)->fetch();
        return (int) $result->last_id;
    }
}
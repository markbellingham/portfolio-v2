<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class People
{
    protected $db;

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
        $sql = "SELECT id, name, uuid, admin FROM users";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
    }

    /**
     * @param string $column
     * @param string $value
     * @return bool|object
     */
    public function findUserByValue(string $column, string $value)
    {
        if( !in_array( $column, ['id','name','uuid'] )) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT id, name, uuid, admin FROM users WHERE $column = ?");
        $stmt->execute([$value]);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
        return $stmt->fetch();
    }

    /**
     * @param $user
     * @return User
     */
    public function saveUser(User $user)
    {
        $params = [$user->getName(), $user->getUuid()];
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
<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class People
{
    protected $db;
    protected $data;

    public function __construct()
    {
        $this->db = MyPDO::instance('People');
    }

    public function findAllUsers()
    {
        $sql = "SELECT id, name, cookie_ref FROM users";
        return $this->db->run($sql)->fetchAll();
    }

    public function findUserByCookie($cookie)
    {
        $params = [$cookie];
        $sql = "SELECT id, name, cookie_ref FROM users WHERE cookie_ref = ?";
        return $this->db->run($sql, $params)->fetch();
    }

    public function findUserById($id)
    {
        $params = [$id];
        $sql = "SELECT id, name, cookie_ref FROM users WHERE id = ?";
        return $this->db->run($sql, $params)->fetch();
    }
}
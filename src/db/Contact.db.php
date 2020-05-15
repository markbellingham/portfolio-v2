<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class Contact
{
    private $db;
    private $data;

    public function __construct()
    {
        $this->db = MyPDO::instance('Contact');
    }

    public function getIcons($qty)
    {
        $params = [$qty];
        $sql = "SELECT icon_id, icon, name, colour
                FROM icons
                ORDER BY RAND()
                LIMIT ?";
        return $this->db->run($sql, $params)->fetchAll();
    }
}
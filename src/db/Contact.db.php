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

    public function getIcons()
    {
        $sql = "SELECT icon_id, icon, name, colour
                FROM icons
                ORDER BY RAND()
                LIMIT 6";
        return $this->db->run($sql)->fetchAll();
    }
}
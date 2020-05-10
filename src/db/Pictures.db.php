<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class Pictures
{
    private $db;
    private $data;

    public function __construct()
    {
        $this->db = MyPDO::instance('Pictures');
    }

    /**
     * Get all photos (width and height is for thumbnails)
     * @return array
     */
    public function findAll()
    {
        $sql = "SELECT p.id, p.title, p.description, p.town, c.name AS country, p.filename, p.width, p.height
                FROM photos p
                JOIN countries c ON c.Id = p.country
                ORDER BY RAND()";
        return $this->db->run($sql)->fetchAll();
    }

    /**
     * Get one photo
     * @param int $photoId
     * @return object
     */
    public function findOne(int $photoId)
    {
        $params = [$photoId];
        $sql = "SELECT p.id, p.title, p.description, p.town, c.name, p.filename
                FROM photos p
                JOIN countries c ON c.Id = p.country
                WHERE p.id = ?";
        return $this->db->run($sql, $params)->fetch();
    }



}
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
        $sql = "SELECT p.id, p.title, p.description, p.town, c.name AS country, p.filename, p.width, p.height,
                    cmt.comment, IFNULL(cmt.cmt_count, 0) AS cmt_count, IFNULL(fv.fave_count, 0) AS fave_count
                FROM photos p
                JOIN countries c ON c.Id = p.country
                LEFT JOIN (
                    SELECT id, user_id, photo_id, comment, COUNT(comment) AS cmt_count
                    FROM user_comments 
                ) AS cmt ON cmt.photo_id = p.id
                LEFT JOIN (
                    SELECT user_id, photo_id, COUNT(user_id) AS fave_count
                    FROM user_faves
                ) AS fv ON fv.photo_id = p.id
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
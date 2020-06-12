<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class Pictures
{
    private $db;

    public function __construct()
    {
        $this->db = MyPDO::instance('Pictures');
    }

    /**
     * Get all photos (width and height is for thumbnails)
     * @param string $directory
     * @return array
     */
    public function findAll(string $directory = 'Favourites')
    {
        $params = [$directory];
        $sql = "SELECT p.id, p.title, p.description, p.town, c.name AS country, p.filename, p.directory, p.width, p.height,
                    IFNULL(cmt.cmt_count, 0) AS comment_count, IFNULL(fv.fave_count, 0) AS fave_count
                FROM photos p
                JOIN countries c ON c.Id = p.country
                LEFT JOIN (
                    SELECT photo_id, COUNT(comment) AS cmt_count
                    FROM photo_comments 
                    GROUP BY photo_id
                ) AS cmt ON cmt.photo_id = p.id
                LEFT JOIN (
                    SELECT photo_id, COUNT(user_id) AS fave_count
                    FROM photo_faves
                    GROUP BY photo_id
                ) AS fv ON fv.photo_id = p.id   
                WHERE p.directory = ?
                ORDER BY RAND()";
        return $this->db->run($sql, $params)->fetchAll();
    }

    /**
     * Get one photo
     * @param int $photoId
     * @return object
     */
    public function findOne(int $photoId)
    {
        $params = [$photoId];
        $sql = "SELECT p.id, p.title, p.description, p.town, c.name, p.filename, p.directory,
                    IFNULL(cmt.cmt_count, 0) AS comment_count, IFNULL(fv.fave_count, 0) AS fave_count
                FROM photos p
                JOIN countries c ON c.Id = p.country
                LEFT JOIN (
                    SELECT photo_id, COUNT(comment) AS cmt_count                    
                    FROM photo_comments
                    GROUP BY photo_id
                ) AS cmt ON cmt.photo_id = p.id
                LEFT JOIN (
                    SELECT photo_id, COUNT(user_id) AS fave_count
                    FROM photo_faves
                    GROUP BY photo_id
                ) AS fv ON fv.photo_id = p.id
                WHERE p.id = ?";
        return $this->db->run($sql, $params)->fetch();
    }

    /**
     * Get a list of comments for one photo
     * @param $photoId
     * @return array
     */
    public function getPhotoComments($photoId)
    {
        $params = [$photoId];
        $sql = "SELECT pc.id, pc.user_id, u.name, pc.photo_id, pc.comment, DATE_FORMAT(pc.created, 'd-m-Y @ HH:mm') AS created
                FROM photo_comments pc
                JOIN people.users u ON pc.user_id = u.id
                WHERE pc.photo_id = ?
                ORDER BY pc.created";
        return $this->db->run($sql, $params)->fetchAll();
    }


    public function savePhotoComment($comment)
    {
        $params = [$comment->userId, $comment->photoId, $comment->comment];
        $sql = "INSERT INTO photo_comments (user_id, photo_id, comment) VALUES (?,?,?)";
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }


}
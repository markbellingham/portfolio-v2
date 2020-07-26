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



    /*********************************************
     * Functions that GET data from the database
     *********************************************/

    /**
     * Get all photos (width and height is for thumbnails). Optional search parameters.
     * @param string $searchTerm
     * @param string $directory
     * @return array
     */
    public function findAll(string $searchTerm = "", string $directory = 'Favourites')
    {
        $fuzzySearch = $searchTerm == "" ? "" : '+'.$searchTerm.'*';
        $params = [$fuzzySearch, $fuzzySearch, $directory];
        $sql = "SELECT p.id, p.title, p.description, p.town, c.name AS country, p.filename, p.directory, p.width, p.height,
                    IFNULL(cmt.cmt_count, 0) AS comment_count, IFNULL(fv.fave_count, 0) AS fave_count,       
                    MATCH(p.title, p.description, p.town) AGAINST(? IN BOOLEAN MODE) AS pscore,
                    MATCH(c.name) AGAINST(? IN BOOLEAN MODE) AS cscore
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
                WHERE p.directory = ?";
        if($searchTerm != "") {
            array_push($params, $fuzzySearch, $fuzzySearch);
            $sql .= " AND MATCH(p.title, p.description, p.town) AGAINST(? IN BOOLEAN MODE)
                    OR MATCH(c.name) AGAINST(? IN BOOLEAN MODE)
                    ORDER BY (pscore + cscore) DESC";
        } else {
            $sql .= " ORDER BY RAND()";
        }
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
     * @param int $photoId
     * @return array
     */
    public function getPhotoComments(int $photoId)
    {
        $params = [$photoId];
        $sql = "SELECT pc.id, pc.user_id, u.name, pc.photo_id, pc.comment, DATE_FORMAT(pc.created, '%d-%m-%Y @ %H:%i') AS created
                FROM photo_comments pc
                JOIN people.users u ON pc.user_id = u.id
                WHERE pc.photo_id = ?
                ORDER BY pc.created";
        return $this->db->run($sql, $params)->fetchAll();
    }

    /**
     * @param int $photoId
     * @return int
     */
    public function getFaveCount(int $photoId)
    {
        $params = [$photoId];
        $sql = "SELECT COUNT(photo_id) AS fave_count FROM photo_faves WHERE photo_id = ?";
        $result = $this->db->run($sql, $params)->fetch();
        return $result->fave_count;
    }

    /**
     * @param int|null $photoId
     * @return array
     */
    public function getTags(int $photoId = null)
    {
        $sql = "SELECT tags.id, tags.tag FROM tags";
        if($photoId) {
            $params = [$photoId];
            $sql .= " JOIN photo_tags ON photo_tags.tag_id = tags.id
                     WHERE photo_tags.photo_id = ?";
            return $this->db->run($sql, $params)->fetchAll();
        } else {
            return $this->db->run($sql)->fetchAll();
        }
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




    /*****************************************************
     * Functions that SAVE or UPDATE data in the database
     *****************************************************/

    /**
     * @param object $comment
     * @return bool
     */
    public function savePhotoComment(object $comment)
    {
        $params = [$comment->userId, $comment->photoId, $comment->comment];
        $sql = "INSERT INTO photo_comments (user_id, photo_id, comment) VALUES (?,?,?)";
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }

    /**
     * @param object $fave
     * @return bool
     */
    public function saveFave(object $fave)
    {
        $params = [$fave->userId, $fave->photoId];
        $sql = "INSERT INTO photo_faves (user_id, photo_id) VALUES (?,?)";
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }

    /**
     * @param Tag $tag
     * @return bool
     */
    public function saveTag(Tag $tag)
    {
        $params = [$tag->getTag()];
        $sql = "INSERT INTO tags (tag) values (?)";
        $this->db->run($sql, $params);
        return $this->db->error ? false : $this->getLastInsertId();
    }

    /**
     * @param int $photoId
     * @param int $tagId
     * @return bool
     */
    public function savePhotoTag(int $photoId, int $tagId)
    {
        $params = [$photoId, $tagId];
        $sql = "INSERT INTO photo_tags (photo_id, tag_id) VALUES (?, ?)";
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }



    /**********************************************
     * Functions that DELETE data in the database
     **********************************************/

}
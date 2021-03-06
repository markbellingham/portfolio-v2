<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class Pictures
{
    private MyPDO $db;

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
    public function findAll(string $searchTerm = "", string $directory = 'Favourites'): array
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
    public function findOne(int $photoId): object
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
    public function getPhotoComments(int $photoId): array
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
    public function getFaveCount(int $photoId): int
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
    public function getTags(int $photoId = null): array
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




    /*****************************************************
     * Functions that SAVE or UPDATE data in the database
     *****************************************************/

    /**
     * @param Comment $comment
     * @return bool
     */
    public function savePhotoComment(Comment $comment): bool
    {
        $params = [$comment->getUserId(), $comment->getItemId(), $comment->getComment()];
        $sql = "INSERT INTO photo_comments (user_id, photo_id, comment) VALUES (?,?,?)";
        $this->db->run($sql, $params);
        return $this->db->errors() ? false : true;
    }

    /**
     * @param Favourite $fave
     * @return bool
     */
    public function saveFave(Favourite $fave): bool
    {
        $params = [$fave->getUserId(), $fave->getItemId()];
        $sql = "INSERT INTO photo_faves (user_id, photo_id) VALUES (?,?)";
        $this->db->run($sql, $params);
        return $this->db->errors() ? false : true;
    }

    /**
     * @param Tag $tag
     * @return bool|Tag
     */
    public function saveTag(Tag $tag)
    {
        $params = [$tag->getTag()];
        $sql = "INSERT INTO tags (tag) values (?)";
        $this->db->run($sql, $params);
        if($this->db->errors()) {
            return false;
        }
        $tag->setTagId($this->db->lastInsertId());
        return $tag;
    }

    /**
     * @param $photoId
     * @param Tag $tag
     * @return bool
     */
    public function savePhotoTag($photoId, Tag $tag): bool
    {
        $params = [$photoId, $tag->getTagId()];
        $sql = "INSERT INTO photo_tags (photo_id, tag_id) VALUES (?, ?)";
        $this->db->run($sql, $params);
        return $this->db->errors() ? false : true;
    }



    /**********************************************
     * Functions that DELETE data in the database
     **********************************************/

}
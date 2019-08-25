<?php
namespace Albums;
require_once(__DIR__."/../../src/appInit.php");
use MyPDO\MyPDO;

class Albums
{
    protected $db;
    protected $data;

    public function __construct()
    {
        $this->db = MyPDO::instance();
    }

    public function findAll()
    {
        $sql = "SELECT al.album_id, al.image, ar.artist, al.title, al.year, g.genre
            FROM albums al
            JOIN artists ar ON al.artist_id = ar.artist_id
            JOIN genres g ON g.genre_id = al.genre_id
            ORDER BY ar.artist, al.year, al.album_id";
        $this->data = $this->db->run($sql)->fetchAll();
        return $this->data;
    }

    public function findOne($albumId)
    {
        $params = [$albumId];
        $sql = "SELECT al.album_id, al.image, ar.artist, al.title, al.year, g.genre 
            FROM albums al
            JOIN artists ar ON al.artist_id = ar.artist_id
            JOIN genres g ON g.genre_id = al.genre_id
            WHERE al.album_id = ?
            ORDER BY ar.artist, al.year, al.album_id";
        $this->data = $this->db->run($sql, $params)->fetch();
        return $this->data;
    }

    public function getTracks($trackId)
    {
        $params = [$trackId];
        $sql = "SELECT track_no, track_name, duration, filename FROM tracks WHERE album_id = ? ORDER BY track_no";
        $this->data = $this->db->run($sql, $params)->fetchAll();
        return $this->data;
    }
}
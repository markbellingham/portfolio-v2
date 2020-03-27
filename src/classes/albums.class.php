<?php
namespace Albums;
use MyPDO\MyPDO;

class Albums
{
    protected $db;
    protected $data;

    public function __construct()
    {
        $this->db = MyPDO::instance();
    }

    /**
     * Get all albums
     * @return array
     */
    public function findAll()
    {
        $sql = "SELECT al.album_id, al.image, ar.artist, al.title, al.year, g.genre
            FROM albums al
            LEFT JOIN artists ar ON al.artist_id = ar.artist_id
            LEFT JOIN genres g ON g.genre_id = al.genre_id
            ORDER BY ar.artist, al.year, al.album_id";
        $this->data = $this->db->run($sql)->fetchAll();
        return $this->data;
    }

    /**
     * Get a single album
     * @param $albumId
     * @return object
     */

    public function findOne($albumId)
    {
        $params = [$albumId];
        $sql = "SELECT al.album_id, al.image, ar.artist, al.title, al.year, g.genre 
            FROM albums al
            LEFT JOIN artists ar ON al.artist_id = ar.artist_id
            LEFT JOIN genres g ON g.genre_id = al.genre_id
            WHERE al.album_id = ?
            ORDER BY ar.artist, al.year, al.album_id";
        return $this->db->run($sql, $params)->fetch();
    }

    /**
     * Get all tracks for one album
     * @param $albumId
     * @return array
     */
    public function getTracks($albumId)
    {
        $params = [$albumId];
        $sql = "SELECT t.trackId, t.track_no, t.track_name, t.duration, t.filename  
                FROM tracks t
                WHERE t.album_id = ? 
                ORDER BY track_no";
        return $this->db->run($sql, $params)->fetchAll();
    }

    /**
     * Get data about a single track
     * @param $trackId
     * @return object
     */
    public function getOneTrack($trackId)
    {
        $params = [$trackId];
        $sql = "SELECT trackId, track_no, track_name, duration, filename 
                FROM tracks 
                WHERE trackId = ?";
        return $this->db->run($sql, $params)->fetch();
    }
}
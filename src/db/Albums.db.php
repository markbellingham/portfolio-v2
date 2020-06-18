<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class Albums
{
    protected $db;
    protected $data;

    public function __construct()
    {
        $this->db = MyPDO::instance('Music');
    }

    /**
     * Get all albums
     * @return array
     */
    public function findAll()
    {
        $sql = "SELECT al.album_id, al.image, al.album_artist, ar.artist, al.title, al.year, g.genre, al.top50, al.playcount
                FROM albums al
                LEFT JOIN artists ar ON al.artist_id = ar.artist_id
                LEFT JOIN genres g ON g.genre_id = al.genre_id
                ORDER BY ar.artist, al.year, al.album_id";
        return $this->db->run($sql)->fetchAll();
    }

    /**
     * Get a single album
     * @param $albumId
     * @return object
     */

    public function findOne($albumId)
    {
        $params = [$albumId];
        $sql = "SELECT al.album_id, al.image, ar.artist, al.title, al.year, g.genre, al.top50 AS album_top50, 
                    al.playcount AS album_playcount, ar.top50 AS artist_top50, ar.playcount AS artist_playcount  
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
        $sql = "SELECT t.trackId, t.track_no, t.track_name, t.duration, t.filename, al.title, al.image, ar.artist,
                    t.top50 AS track_top50, t.playcount AS track_playcount, ar.top50 AS artist_top50,       
                    ar.playcount AS artist_playcount, al.top50 AS album_top50, al.playcount AS album_playcount
                FROM tracks t
                JOIN albums al ON t.album_id = al.album_id
                JOIN artists ar ON al.artist_id = ar.artist_id
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
        $sql = "SELECT t.trackId, t.track_no, t.track_name, t.duration, t.filename, al.title, al.image, ar.artist,
                    t.top50 AS track_top50, t.playcount AS track_playcount, ar.top50 AS artist_top50,       
                    ar.playcount AS artist_playcount, al.top50 AS album_top50, al.playcount AS album_playcount
                FROM tracks t
                JOIN albums al ON t.album_id = al.album_id
                JOIN artists ar ON al.artist_id = ar.artist_id
                WHERE trackId = ?";
        return $this->db->run($sql, $params)->fetch();
    }

    public function getArtistByName($artistName)
    {
        $params = ['%'.$artistName.'%'];
        $sql = "SELECT artist_id, artist FROM artists WHERE artist LIKE ?";
        return $this->db->run($sql, $params)->fetch();
    }

    public function saveTop50Album($rank, $data)
    {
        $artist = $this->getArtistByName($data->artist->name);
        $params = [$rank, $data->playcount, '%'.$data->name.'%', $artist->artist_id];
        $sql = "UPDATE albums SET top50 = ?, playcount = ? WHERE title LIKE ? AND artist_id = ?";
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }

    public function saveTop50Artist($rank, $data)
    {
        $params = [$rank, $data->playcount, '%'.$data->name.'%'];
        $sql = "UPDATE artists SET top50 = ?, playcount = ? WHERE artist LIKE ?";
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }

    public function saveTop50Track($rank, $data)
    {
        $artist = $this->getArtistByName($data->artist->name);
        $params = [$rank, $data->playcount, '%'.$data->name.'%', $artist->artist_id];
        $sql = "UPDATE tracks SET top50 = ?, playcount = ? WHERE track_name LIKE ?";
        if($artist) {
            $sql .= " AND artist_id = ?";
        }
        $this->db->run($sql, $params);
        return $this->db->error ? false : true;
    }

    public function clearTop50($table) {
        if(!in_array($table, ['albums','artists','tracks'])) { return false; }
        $sql = "UPDATE $table SET top50 = NULL";
        $this->db->run($sql);
        return $this->db->error ? false : true;
    }
}
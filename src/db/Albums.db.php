<?php
require_once('database.class.php');
use MyPDO\MyPDO;

class Albums
{
    private MyPDO $db;

    public function __construct()
    {
        $this->db = MyPDO::instance('Music');
    }


    /*****************************
     * Functions that GET data
     *****************************/

    /**
     * Get all albums
     * @param string $filter
     * @return array
     */
    public function findAll(string $filter): array
    {
        $sql = "SELECT al.album_id, al.image, al.album_artist, ar.artist, al.title, al.year, g.genre, al.top50 AS album_top50,
                    al.playcount AS album_playcount, ar.top50 AS artist_top50, ar.playcount AS artist_playcount
                FROM albums al
                LEFT JOIN artists ar ON al.artist_id = ar.artist_id
                LEFT JOIN genres g ON g.genre_id = al.genre_id";
        switch($filter) {
            case 'top50artists':
                $sql .= " WHERE ar.top50 > 0 
                    GROUP BY ar.artist, al.image, al.album_artist, al.album_id, al.title, al.year, g.genre, al.top50, al.playcount, ar.top50, ar.playcount
                    HAVING al.playcount = MAX(al.playcount) 
                    ORDER BY ar.top50, al.playcount";
                break;
            case 'top50albums':
                $sql .= " WHERE al.top50 > 0 ORDER BY al.top50";
                break;
            case 'all':
                $sql .= " ORDER BY ar.artist, al.year, al.album_id";
                BREAK;
        }
        return $this->db->run($sql)->fetchAll();
    }

    /**
     * Get a single album
     * @param $albumId
     * @return object
     */
    public function findOne($albumId): object
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
    public function getTracks($albumId): array
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
    public function getOneTrack($trackId): object
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

    /**
     * Return an artist object given a name (fuzzy search)
     * @param string $artistName
     * @return object|bool
     */
    public function getArtistByName(string $artistName)
    {
        $params = ['%'.$artistName.'%', $artistName];
        $sql = "SELECT artist_id, artist 
                FROM artists 
                WHERE artist LIKE ?
                ORDER BY INSTR(artist, ?), artist";
        return $this->db->run($sql, $params)->fetch();
    }

    public function getTop50tracks(): array
    {
        $sql = "SELECT 
                    t.top50             AS track_top50, 
                    MAX(t.trackId)      AS trackId, 
                    MAX(al.album_id)    AS album_id, 
                    MAX(al.image)       AS image, 
                    MAX(ar.artist)      AS album_artist, 
                    MAX(t.track_name)   AS title, 
                    MAX(al.year)        AS year, 
                    MAX(g.genre)        AS genre,
                    MAX(t.playcount)    AS track_playcount, 
                    MAX(al.top50)       AS album_top50, 
                    MAX(al.playcount)   AS album_playcount, 
                    MAX(ar.top50)       AS artist_top50, 
                    MAX(ar.playcount)   AS artist_playcount
                FROM tracks t
                LEFT JOIN albums al ON t.album_id = al.album_id
                LEFT JOIN artists ar ON t.artist_id = ar.artist_id
                LEFT JOIN genres g ON al.genre_id = g.genre_id
                WHERE t.top50 > 0
                GROUP BY t.top50
                ORDER BY t.top50";
        return $this->db->run($sql)->fetchAll();
    }


    /**************************************
     * Functions that SAVE or UPDATE data
     **************************************/

    /**
     * @param int $rank
     * @param object $data
     * @return int
     */
    public function saveTop50Album(int $rank, object $data): int
    {
        $artist = $this->getArtistByName($data->artist->name);
        $params = [$rank, $data->playcount, '%'.$data->name.'%'];
        $sql = "UPDATE albums SET top50 = ?, playcount = ? WHERE title LIKE ?";
        if($artist) {
            $params[] = $artist->artist_id;
            $sql .= " AND artist_id = ?";
        }
        $this->db->run($sql, $params);
        return $this->db->affectedRows();
    }

    /**
     * @param int $rank
     * @param object $data
     * @return int
     */
    public function saveTop50Artist(int $rank, object $data): int
    {
        $params = [$rank, $data->playcount, '%'.$data->name.'%'];
        $sql = "UPDATE artists SET top50 = ?, playcount = ? WHERE artist LIKE ?";
        $this->db->run($sql, $params);
        return $this->db->affectedRows();
    }

    /**
     * @param int $rank
     * @param object $data
     * @return int
     */
    public function saveTop50Track(int $rank, object $data): int
    {
        $artist = $this->getArtistByName($data->artist->name);
        $params = [$rank, $data->playcount, '%'.$data->name.'%'];
        $sql = "UPDATE tracks SET top50 = ?, playcount = ? WHERE track_name LIKE ?";
        if($artist) {
            $params[] = $artist->artist_id;
            $sql .= " AND artist_id = ?";
        }
        $this->db->run($sql, $params);
        return $this->db->affectedRows();
    }


    /************************************
     * Functions that DELETE data
     ************************************/

    /**
     * @param string $table
     * @return bool
     */
    public function clearTop50(string $table): bool
    {
        if(!in_array($table, ['albums','artists','tracks'])) { return false; }
        $sql = "UPDATE $table SET top50 = NULL";
        $this->db->run($sql);
        return $this->db->errors() ? false : true;
    }
}
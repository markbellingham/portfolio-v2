<?php

class LastFmController
{
    private $rootUrl = '';
    private $username = '';
    private $apiKey = '';
    private $sharedSecret = '';
    private $response = array(
        'albums' => '',
        'artists' => '',
        'tracks' => ''
    );

    public function __construct()
    {
        $configs = include '../../config/config.php';
        $lastFmConfig = $configs['lastfm_api'];
        $this->rootUrl = $lastFmConfig['root_url'];
        $this->username = $lastFmConfig['username'];
        $this->apiKey = $lastFmConfig['api_key'];
        $this->sharedSecret = $lastFmConfig['shared_secret'];
    }

    /**
     * @param string $format
     * @param string $action
     * @return string[]
     */
    public function refreshData( string $format = 'json', string $action = 'save')
    {
        $albumsUrl = $this->rootUrl.'gettopalbums&user='.$this->username.'&api_key='.$this->apiKey.'&format='.$format;
        $artistsUrl = $this->rootUrl.'gettopartists&user='.$this->username.'&api_key='.$this->apiKey.'&format='.$format;
        $tracksUrl = $this->rootUrl.'gettoptracks&user='.$this->username.'&api_key='.$this->apiKey.'&format='.$format;

        $this->response['albums'] = $this->getApiData($albumsUrl);
        $this->response['artists'] = $this->getApiData($artistsUrl);
        $this->response['tracks'] = $this->getApiData($tracksUrl);

        $albums = new Albums();
        foreach($this->response as $key => $value) {
            $jsonDecoded = json_decode($value);
            switch($key) {
                case 'albums':
                    $albums->clearTop50('albums');
                    $albumData = $jsonDecoded->topalbums->album;
                    foreach($albumData as $rank => $data) {
                        $albums->saveTop50Album($rank, $data);
                    }
                    break;
                case 'artists':
                    $albums->clearTop50('artists');
                    $artistData =  $jsonDecoded->topartists->artist;
                    foreach($artistData as $rank => $data) {
                        $albums->saveTop50Artist($rank, $data);
                    }
                    break;
                case 'tracks':
                    $albums->clearTop50('tracks');
                    $trackData = $jsonDecoded->toptracks->track;
                    foreach($trackData as $rank => $data) {
                        $albums->saveTop50Track($rank, $data);
                    }
            }
        }

        if($action == 'return') {
            return $this->response;
        } else if($action == 'save') {
            $date = date('Y-m-d');
            foreach($this->response as $key => $value) {
                $filename = $_SERVER['DOCUMENT_ROOT'] . '/playlists/json/top-' . $key . '.json';
                $jsonDecoded = json_decode($value);
                file_put_contents($filename, json_encode(["date" => $date, "data" => $jsonDecoded]));
            }
        }
    }

    private function getApiData($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return $response;
    }

}
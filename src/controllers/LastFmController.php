<?php

class LastFmController
{
    private string $rootUrl = '';
    private string $username = '';
    private string $apiKey = '';
    private string $sharedSecret = '';
    private array $notSaved = [
        'albums' => [],
        'artists' => [],
        'tracks' => []
    ];
    private array $usageData = array(
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
    public function refreshData( string $format = 'json', string $action = 'save'): array
    {
        $this->getRemoteDataFromLastFM($format);
        $this->saveUsageDataLocally();
        $this->outputFailedSaves();

        if($action == 'save') {
            $this->saveCopyOfResponse();
        }
        return $this->usageData;
    }

    /**
     * @param string $format
     */
    private function getRemoteDataFromLastFM(string $format)
    {
        $albumsUrl = $this->rootUrl.'gettopalbums&user='.$this->username.'&api_key='.$this->apiKey.'&format='.$format;
        $artistsUrl = $this->rootUrl.'gettopartists&user='.$this->username.'&api_key='.$this->apiKey.'&format='.$format;
        $tracksUrl = $this->rootUrl.'gettoptracks&user='.$this->username.'&api_key='.$this->apiKey.'&format='.$format;

        $this->usageData['albums'] = $this->getApiData($albumsUrl);
        $this->usageData['artists'] = $this->getApiData($artistsUrl);
        $this->usageData['tracks'] = $this->getApiData($tracksUrl);
    }

    private function saveUsageDataLocally()
    {
        $albums = new Albums();
        foreach($this->usageData as $key => $value) {
            $jsonDecoded = json_decode($value, false, 512, JSON_UNESCAPED_UNICODE);
            $albums->clearTop50($key);
            switch($key) {
                case 'albums':
                    $albumData = $jsonDecoded->topalbums->album;
                    foreach($albumData as $rank => $data) {
                        $found = $albums->saveTop50Album($rank + 1, $data);
                        if($found < 1) {
                            $this->notSaved['albums'][] = ['album' => $data->name, 'artist' => $data->artist->name];
                        }
                    }
                    break;
                case 'artists':
                    $artistData =  $jsonDecoded->topartists->artist;
                    foreach($artistData as $rank => $data) {
                        $found = $albums->saveTop50Artist($rank + 1, $data);
                        if($found < 1) {
                            $this->notSaved['artists'][] = ['artist' => $data->name];
                        }
                    }
                    break;
                case 'tracks':
                    $trackData = $jsonDecoded->toptracks->track;
                    foreach($trackData as $rank => $data) {
                        $found = $albums->saveTop50Track($rank + 1, $data);
                        if($found < 1) {
                            $this->notSaved['tracks'][] = ['track' => $data->name, 'artist' => $data->artist->name];
                        }
                    }
            }
        }
    }

    private function outputFailedSaves()
    {
        $errors = count($this->notSaved['albums']) > 0 || count($this->notSaved['artists']) > 0 || count($this->notSaved['tracks']) > 0;
        if( $errors ) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '../src/temp/' . date('Ymd') . '-most-played-not-saved.txt';
            file_put_contents($filename, print_r($this->notSaved, true));
            chmod($filename, 0775);
        }
    }

    private function saveCopyOfResponse()
    {
        $date = date('Y-m-d');
        foreach($this->usageData as $key => $value) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/music/json/top-' . $key . '.json';
            $jsonDecoded = json_decode($value);
            file_put_contents($filename, json_encode(["date" => $date, "data" => $jsonDecoded]));
        }
    }

    /**
     * @param $url
     * @return bool|string
     */
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
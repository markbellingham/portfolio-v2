<?php

class MusicController {

    private $requestMethod;
    private $params;
    private $response = [];

    /**
     * MusicController constructor.
     * @param array $request
     * @param string $requestMethod
     */
    public function __construct(array $request, string $requestMethod)
    {
        $this->requestMethod = $requestMethod ?? 'GET';
        $this->params = $request;
    }

    public function fulfilRequest()
    {
        $action = strtolower($this->requestMethod);
        call_user_func(array($this, $action));
        return $this->response;
    }

    private function get()
    {
        $albums = new Albums();
        switch($this->params['endpoint']) {
            case 'albums':
                if($this->params['ref'] == 'top50tracks') {
                    $this->response['data'] = $albums->getTop50tracks();
                } else {
                    $this->response['data'] = $albums->findAll($this->params['ref']);
                }
                break;
            case 'album':
                $this->response['data'] = $albums->findOne($this->params['ref']);
                break;
            case 'tracks':
                $this->response['data'] = $albums->getTracks($this->params['ref']);
                break;
            case 'track':
                $this->response['data'] = $albums->getOneTrack($this->params['ref']);
        }
    }

    private function post()
    {
        return '';
    }

    private function put()
    {
        return '';
    }

    private function delete()
    {
        return '';
    }
}

<?php

class MusicController {

    private $requestType;
    private $params;
    private $response = '';

    public function __construct($request)
    {
        $this->requestType = array_shift($request) ?? 'get';
        $this->params = $request;
    }

    public function fulfilRequest()
    {
        switch($this->requestType) {
            case 'get':
                $this->get();
                break;
            case 'post':
                $this->post();
                break;
            case 'put':
                $this->put();
                break;
            case 'delete':
                $this->delete();
                break;
        }
        return $this->response;
    }

    private function get()
    {
        $albums = new Albums();
        switch($this->params[0]) {
            case 'albums':
                $this->response = $albums->findAll();
                break;
            case 'album':
                $this->response = $albums->findOne($this->params[1]);
                break;
            case 'tracks':
                $this->response = $albums->getTracks($this->params[1]);
                break;
            case 'track':
                $this->response = $albums->getOneTrack($this->params[1]);
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

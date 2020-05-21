<?php

class MusicController {

    private $requestMethod;
    private $params;
    private $response = '';

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
                $this->response = $albums->findAll();
                break;
            case 'album':
                $this->response = $albums->findOne($this->params['id']);
                break;
            case 'tracks':
                $this->response = $albums->getTracks($this->params['id']);
                break;
            case 'track':
                $this->response = $albums->getOneTrack($this->params['id']);
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

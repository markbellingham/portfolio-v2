<?php

class MusicController {

    private $requestMethod;
    private $params;
    private $response = '';

    /**
     * MusicController constructor.
     * @param array $request
     * @param string|null $requestMethod
     */
    public function __construct(array $request, string $requestMethod = null)
    {
        $this->requestMethod = $requestMethod ?? 'GET';
        $this->params = $request;
    }

    public function fulfilRequest()
    {
        switch($this->requestMethod) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->post();
                break;
            case 'PUT':
                $this->put();
                break;
            case 'DELETE':
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

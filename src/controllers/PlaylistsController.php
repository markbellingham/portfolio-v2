<?php

class PlaylistsController
{
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
        return '';
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
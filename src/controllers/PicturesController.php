<?php

class PicturesController
{
    private $requestType = 'GET';
    private $params = [];

    public function __construct($request)
    {
        $this->requestType = array_shift($request) ?? 'get';
        $this->params = $request;
    }

    public function fulfilRequest()
    {
        $response = '';
        switch($this->requestType) {
            case 'get':
                $response = $this->get();
                break;
            case 'post':
                $response = $this->post();
                break;
            case 'put':
                $response = $this->put();
                break;
            case 'delete':
                $response = $this->delete();
                break;
        }
        return $response;
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
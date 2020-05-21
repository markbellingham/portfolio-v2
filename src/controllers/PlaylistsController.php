<?php

class PlaylistsController
{
    private $requestMethod;
    private $params;
    private $response = '';

    public function __construct($request)
    {
        $this->requestMethod = array_shift($request) ?? 'GET';
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
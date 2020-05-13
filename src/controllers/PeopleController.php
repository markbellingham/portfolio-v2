<?php

class PeopleController
{
    private $requestMethod;
    private $params;
    private $response = '';

    public function __construct(array $request, string $requestMethod)
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
        $people = new People();
        switch($this->params['end_point']) {
            case 'users':
                $this->response = $people->findAllUsers();
                break;
            case 'user':
                $this->response = $people->findUserByCookie($this->params['id']);
                break;
        }
    }

    private function post()
    {

    }

    private function put()
    {

    }

    private function delete()
    {

    }
}
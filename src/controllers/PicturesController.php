<?php

class PicturesController
{
    private $requestMethod;
    private $params;
    private $response = '';

    /**
     * PicturesController constructor.
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
        $pictures = new Pictures();
        switch($this->params[0]) {
            case 'pictures':
                $this->response = $pictures->findAll();
                break;
            case 'picture':
                $this->response = $pictures->findOne($this->response[1]);
                break;
            case 'search':

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
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
        switch($this->params['end_point']) {
            case 'photos':
                $this->response = $pictures->findAll();
                break;
            case 'photo':
                $this->response = $pictures->findOne($this->params['id']);
                $this->response->comments = $pictures->getPhotoComments($this->params['id']);
                break;
            case 'search':
                break;
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
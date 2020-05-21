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
        $action = strtolower($this->requestMethod);
        call_user_func(array($this, $action));
        return $this->response;
    }

    private function get()
    {
        $pictures = new Pictures();
        switch($this->params['endpoint']) {
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
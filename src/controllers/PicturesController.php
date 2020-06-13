<?php

class PicturesController
{
    private $requestMethod;
    private $params;
    private $response = [];

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
                $this->response['data'] = $pictures->findAll();
                break;
            case 'photo':
                $this->response['data'] = $pictures->findOne($this->params['id']);
                $this->response['comments'] = $pictures->getPhotoComments($this->params['id']);
                break;
            case 'search':
                break;
        }
    }

    private function post()
    {
        $success = false;
        $comments = [];
        if($this->commentConditions()) {
            $comment = (object) [
                'userId' => $this->params['userId'],
                'photoId' => $this->params['id'],
                'comment' => $this->params['values']['comment']
            ];
            $pictures = new Pictures();
            $success =  $pictures->savePhotoComment($comment);
            if($success) {
                $comments = $pictures->getPhotoComments($comment->photoId);
            }
        }
        $this->response = ['success' => $success, 'comments' => $comments];
    }

    private function put()
    {
        return '';
    }

    private function delete()
    {
        return '';
    }

    private function commentConditions()
    {
        $fn = new Functions();
        $paramValues = $this->params['values'];

        if(!$fn->requestedByTheSameDomain($paramValues['secret'])) {
            return false;
        }

        $this->params['userId'] = $fn->validateUser($_COOKIE['settings']);
        if(!$this->params['userId']) {
            return false;
        }

        if(strlen($paramValues['description']) > 0) {
            return false; // honey trap
        }

        if((int) $paramValues['icon'] != $paramValues['chosenIcon']['icon_id']) {
            return false; // captcha
        }

        try {
            $stringValidator = new StringValidator();
            $this->params['values']['comment'] = $stringValidator->validate($paramValues['comment']);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
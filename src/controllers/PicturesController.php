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
                $comments = $pictures->getPhotoComments($this->params['id']);
                $this->response['comment_count'] = count($comments);
                $this->response['fave_count'] = $pictures->getFaveCount($this->params['id']);
                break;
            case 'search':
                break;
        }
    }

    private function post()
    {
        switch($this->params['values']['task']) {
            case 'addComment':
                $this->add_comment();
                break;
            case 'addFave':
                $this->add_fave();
                break;
        }
    }

    private function put()
    {
        return '';
    }

    private function delete()
    {
        return '';
    }

    /**
     * Add a user comment to a photo
     */
    private function add_comment()
    {
        $success = false;
        $comments = [];
        $commentCount = $faveCount = 0;
        if($this->comment_conditions()) {
            $comment = (object) [
                'userId' => $this->params['userId'],
                'photoId' => $this->params['id'],
                'comment' => $this->params['values']['comment']
            ];
            $pictures = new Pictures();
            $success =  $pictures->savePhotoComment($comment);
            if($success) {
                $comments = $pictures->getPhotoComments($comment->photoId);
                $commentCount = count($comments);
                $faveCount = $pictures->getFaveCount($comment->photoId);
            }
        }
        $this->response = [
            'success' => $success,
            'comments' => $comments,
            'comment_count' => $commentCount,
            'fave_count' => $faveCount
        ];
    }

    /**
     * Add a favourite to a photo for that user
     */
    private function add_fave()
    {
        $success = false;
        $faveCount = $commentCount = 0;
        $people = new People();
        $cookieSettings = json_decode($_COOKIE['settings']);
        $user = $people->findUserByValue('uuid', $cookieSettings->uuid);
        if($this->fave_conditions()) {
            $fave = (object) [
                'userId' => $user->id,
                'photoId' => $this->params['id']
            ];
            $pictures = new Pictures();
            $success = $pictures->saveFave($fave);
            if($success) {
                $comments = $pictures->getPhotoComments($this->params['id']);
                $commentCount = count($comments);
                $faveCount = $pictures->getFaveCount($this->params['id']);
            }
        }
        $this->response = [
            'success' => $success,
            'fave_count' => $faveCount,
            'comment_count' => $commentCount
        ];
    }

    private function comment_conditions()
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

    private function fave_conditions()
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
        return true;
    }
}
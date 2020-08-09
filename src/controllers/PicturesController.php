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
                $this->response['data'] = $pictures->findAll($this->params['ref']);
                break;
            case 'photo':
                $this->response['data'] = $pictures->findOne($this->params['ref']);
                $this->response['comments'] = $pictures->getPhotoComments($this->params['ref']);
                $comments = $pictures->getPhotoComments($this->params['ref']);
                $this->response['comment_count'] = count($comments);
                $this->response['fave_count'] = $pictures->getFaveCount($this->params['ref']);
                $this->response['tags'] = $pictures->getTags($this->params['ref']);
                break;
            case 'photo-tags':
                $this->response['data'] = $pictures->getTags(null);
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
            case 'addTags':
                $this->add_tags();
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
            $comment = new Comment(
                $this->params['ref'],
                $this->params['userId'],
                $this->params['values']['comment']
            );
            $pictures = new Pictures();
            $success =  $pictures->savePhotoComment($comment);
            if($success) {
                $comments = $pictures->getPhotoComments($comment->getItemId());
                $commentCount = count($comments);
                $faveCount = $pictures->getFaveCount($comment->getItemId());
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
            $fave = new Favourite(
                $user->id,
                $this->params['ref']
            );
            $pictures = new Pictures();
            $success = $pictures->saveFave($fave);
            if($success) {
                $comments = $pictures->getPhotoComments($this->params['ref']);
                $commentCount = count($comments);
                $faveCount = $pictures->getFaveCount($this->params['ref']);
            }
        }
        $this->response = [
            'success' => $success,
            'fave_count' => $faveCount,
            'comment_count' => $commentCount
        ];
    }

    private function add_tags()
    {
        $success = false;
        if($this->tag_conditions()) {
            $pictures = new Pictures();
            foreach($this->params['values']['tags'] as $tag) {
                if($tag['id'] == 'new') {
                    $tag = $pictures->saveTag(new Tag($tag['tag']));
                } else {
                    $tag = new Tag($tag['tag'], $tag['id']);
                }
                if(!$tag) { break; }
                $success = $pictures->savePhotoTag($this->params['ref'], $tag);
                if(!$success) { break; }
            }
            $this->response = [
                'success' => $success,
                'tags' => $pictures->getTags(),
                'photo_tags' => $pictures->getTags($this->params['ref'])
            ];
        }
    }

    private function comment_conditions()
    {
        $paramValues = $this->params['values'];

        $formSecurityValidator = new FormSecurityValidator();
        $formSecurity = $formSecurityValidator->validate($paramValues, 'form');
        if($formSecurity['success'] == false) {
            return false;
        }

        $userValidator = new UserValidator();
        $anon = '{"permission": false, "uuid": "95c7cdac-6a6f-44ca-a28f-fc62ef61405d", "username": "Anonymous"}';
        $user = $userValidator->validate($_COOKIE['settings'] ?? $anon, 'cookie');
        if($user) {
            $this->params['userId'] = $user->id;
        } else {
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

        $userValidator = new UserValidator();
        $user = $userValidator->validate($_COOKIE['settings'], 'cookie');
        if($user) {
            $this->params['userId'] = $user->id;
        } else {
            return false;
        }
        return true;
    }

    private function tag_conditions()
    {
        $fn = new Functions();
        $paramValues = $this->params['values'];

        if(!$fn->requestedByTheSameDomain($paramValues['secret'])) {
            return false;
        }

        $userValidator = new UserValidator();
        $user = $userValidator->validate($_COOKIE['settings'], 'cookie');
        if($user) {
            return true;
        } else {
            return false;
        }
    }
}
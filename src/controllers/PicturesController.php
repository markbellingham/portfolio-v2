<?php

class PicturesController
{
    private string $requestMethod;
    private array $params;
    private array $response = [
        'success' => false,
        'comments' => [],
        'comment_count' => 0,
        'fave_count' => 0,
        'message' => ''
    ];

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

    public function fulfilRequest(): array
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

    }

    private function delete()
    {

    }

    /**
     * Add a user comment to a photo
     */
    private function add_comment()
    {
        if($this->comment_conditions()) {
            try {
                $comment = new Comment(
                    $this->params['ref'],
                    $this->params['userId'],
                    $this->params['values']['comment']
                );
                $pictures = new Pictures();
                $success =  $pictures->savePhotoComment($comment);
                $this->response['success'] = $success;
                $this->response['id'] = $this->params['ref'];
                $this->response['comments'] = $pictures->getPhotoComments($this->params['ref']);
                $this->response['comment_count'] = count($this->response['comments']);
                $this->response['fave_count'] = $pictures->getFaveCount($this->params['ref']);
            } catch (Exception $e) {
                $this->response['message'] = $e->getMessage();
            }
        }
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
                $user->getId(),
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
            'id' => $this->params['ref'],
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

    /**
     * Check that the form returned all valid elements
     * Check that the user is allowed to make comments
     * @return bool
     */
    private function comment_conditions(): bool
    {
        $paramValues = $this->params['values'];

        $formSecurityValidator = new FormSecurityValidator();
        $formSecurity = $formSecurityValidator->validate($paramValues, 'form');
        if($formSecurity['success'] == false) {
            return false;
        }

        $userValidator = new UserValidator();
        $anon = '{"permission": false, "uuid": "", "username": "Anonymous"}';
        $user = $userValidator->validate($_COOKIE['settings'] ?? $anon, 'cookie');
        if($user) {
            $this->params['userId'] = $user->getId();
        } else {
            return false;
        }

        return true;
    }

    /**
     * Check that the form returned all valid elements
     * Check that the user is valid
     * @return bool
     */
    private function fave_conditions(): bool
    {
        $fn = new Functions();
        $paramValues = $this->params['values'];

        if(!$fn->requestedByTheSameDomain($paramValues['secret'])) {
            return false;
        }

        $userValidator = new UserValidator();
        $anon = '{"permission": false, "uuid": "95c7cdac-6a6f-44ca-a28f-fc62ef61405d", "username": "Anonymous"}';
        $user = $userValidator->validate($_COOKIE['settings'] ?? $anon, 'cookie');
        if(!$user) {
            return false;
        }

        $this->params['userId'] = $user->getId();
        return true;
    }

    /**
     * Check that the form returns all valid elements
     * Check that the user has permission to set tags
     * @return bool
     */
    private function tag_conditions(): bool
    {
        $fn = new Functions();
        $paramValues = $this->params['values'];

        if(!$fn->requestedByTheSameDomain($paramValues['secret'])) {
            return false;
        }

        $userValidator = new UserValidator();
        $anon = '{"permission": false, "uuid": "", "username": "Anonymous"}';
        $user = $userValidator->validate($_COOKIE['settings'] ?? $anon, 'cookie');
        if($user && $userValidator->isAdmin($user)) {
            return true;
        }

        return false;
    }
}
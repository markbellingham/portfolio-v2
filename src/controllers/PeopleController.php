<?php

class PeopleController
{
    private $requestMethod;
    private $params;
    private $response;

    /**
     * PeopleController constructor.
     * @param array $request
     * @param string $requestMethod
     */
    public function __construct(array $request, string $requestMethod)
    {
        $this->requestMethod = $requestMethod ?? 'GET';
        $this->params = $request;
    }

    /**
     * @return mixed
     */
    public function fulfilRequest()
    {
        $action = strtolower($this->requestMethod);
        call_user_func(array($this, $action));
        return $this->response;
    }

    private function get()
    {
        $people = new People();
        switch($this->params['endpoint']) {
            case 'users':
                $this->response['data'] = $people->findAllUsers();
                break;
            case 'user':
                $this->response['data'] = $people->findUserByValue('uuid', $this->params['id']);
                break;
        }
    }

    private function post()
    {
        $people = new People();
        $fn = new Functions();
        $stringValidator = new StringValidator();
        if($fn->requestedByTheSameDomain($this->params['values']['secret'] ?? '')) {
            $user = new User($this->params['values']['username'], $this->params['values']['uuid']);
            foreach($user as $key => $value) {
                try {
                    $user->$key = $stringValidator->validate($value);
                } catch (Exception $e) {
                    $this->response['message'] = $e;
                    return;
                }
            }
            $this->response['data'] = $people->saveUser($user);
        } else {
            $this->response['message'] = 'Sorry, user registrations via the website interface only';
        }
    }

    private function put()
    {

    }

    private function delete()
    {

    }
}
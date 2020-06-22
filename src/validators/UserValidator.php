<?php

class UserValidator implements Validator
{
    private $response;
    private $errors = [];

    const ERROR_MESSAGE = 'User validation error';

    public function __construct() {}

    /**
     * @param mixed $data
     * @param string $type
     * @return array|bool|object
     */
    public function validate($data, $type = 'cookie')
    {
        switch($type) {
            case 'cookie':
                $this->response = $this->checkCookie($data);
        }
        return $this->response;
    }

    /**
     * @param $cookie
     * @return bool|object
     */
    private function checkCookie($cookie)
    {
        $cookie = is_object($cookie) ? $cookie : json_decode($cookie);
        $people = new People();
        $user = $people->findUserByValue('uuid', $cookie->uuid);
        return $user && $user->name == $cookie->username ? $user : false;
    }
}
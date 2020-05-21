<?php

class StringValidator implements Validator
{

    private $response;
    private $errors = [];

    const ERROR_MESSAGE = 'String validation errors';

    public function __construct() {}

    /**
     * @param string $data
     * @param string $type
     * @return array|string
     * @throws Exception
     */
    public function validate($data, $type = 'string')
    {
        switch($type) {
            case 'string':
                $this->response = $this->cleanTagsFromString($data);
                $this->checkProhibitedLinks($data);
                break;
            case 'email':
                break;
        }
        if(count($this->errors) > 0) {
            throw new Exception(self::ERROR_MESSAGE);
        }
        return $this->response;
    }

    /**
     * @param string $data
     * @return string
     */
    private function cleanTagsFromString(string $data)
    {
        $data = trim($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        return $data;
    }

    /**
     * @param string $data
     */
    private function checkProhibitedLinks(string $data)
    {
        if(strpos($data, 'http')) {
            $this->errors[] = self::ERROR_MESSAGE;
        }
        if(strpos($data, 'www')) {
            $this->errors[] = self::ERROR_MESSAGE;
        }
    }

}
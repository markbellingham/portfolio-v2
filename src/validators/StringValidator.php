<?php

class StringValidator implements Validator
{

    private $response;
    private array $errors = [];

    const ERROR_MESSAGE = 'String validation errors';

    public function __construct() {}

    /**
     * @param $data
     * @param string $type
     * @return array|string
     * @throws Exception
     */
    public function validate($data, $type = 'string')
    {
        switch($type) {
            // change to 'comment'
            case 'string':
                $this->response = $this->cleanTagsFromString($data);
                $this->checkProhibitedLinks($this->response);
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
    private function cleanTagsFromString(string $data): string
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
            return;
        }
        if(strpos($data, 'www')) {
            $this->errors[] = self::ERROR_MESSAGE;
            return;
        }
    }

    private function checkProhibitedWords(string $data): string
    {
        // TODO: fix this
        return $data;
    }

}
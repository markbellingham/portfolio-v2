<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class FormSecurityValidator implements Validator
{
    private $response = array(
        'ht' => false,
        'icon' => false,
        'domain_check' => false,
        'errors' => []
    );
    private $errors = [];

    const ERROR_MESSAGE = 'Security Validation Error';

    public function __construct() {}

    public function validate($data, $type = 'string')
    {
        try {
            $this->checkHoneyTrap($data);
            $this->checkIcon($data);
            $this->requestedByTheSameDomain($data);
        } catch (Exception $e) {
            $this->errors[] = $e;
        }
        return $this->response;
    }

    /**
     * @param array $params
     * @throws Exception
     */
    private function checkHoneyTrap(array $params)
    {
        if(strlen($params['description']) > 0) {
            throw new Exception(self::ERROR_MESSAGE);
        }
        $this->response['ht'] = true;
    }

    /**
     * @param array $params
     * @throws Exception
     */
    private function checkIcon(array $params)
    {
        if((int) $params['icon'] != $params['chosenIcon']['icon_id']) {
            throw new Exception(self::ERROR_MESSAGE);
        }
        $this->response['icon'] = true;
    }

    /**
     * @param $secret
     * @throws Exception
     */
    private function requestedByTheSameDomain($secret)
    {
        if($secret === $_SESSION['server-secret']) {
            throw new Exception(self::ERROR_MESSAGE);
        }
        $this->response['domain_check'] = true;
    }
}
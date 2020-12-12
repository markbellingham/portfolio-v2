<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class FormSecurityValidator implements Validator
{
    private array $response = array(
        'success' => false,
        'errors' => false,
    );

    const ERROR_MESSAGE = 'Security Validation Error';

    public function __construct() {}

    /**
     * @param mixed $data
     * @param string $type
     * @return array|bool[]
     */
    public function validate($data, string $type): array
    {
        try {
            $this->checkHoneyTrap($data);
            $this->checkIcon($data);
            $this->requestedByTheSameDomain($data);
        } catch (Throwable $e) {
            $this->response['errors'] = $e->getMessage();
        }
        $this->response['success'] = $this->response['errors'] ? false : true;
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
    }
}
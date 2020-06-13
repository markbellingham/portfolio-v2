<?php
$configs = require_once '../../config/config.php';

class ContactController {

    private $requestMethod;
    private $params;
    private $response = [];

    /**
     * ContactController constructor.
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
        $contact = new Contact();
        switch($this->params['id']) {
            case 'icons':
                $this->response['data'] = $contact->getIcons($this->params['qty']);
                break;
        }
    }

    private function post()
    {
        return '';
    }

    private function put()
    {
        return '';
    }

    private function delete()
    {
        return '';
    }
}

if(isset($_POST['send-email'])) {
    $data = filter_input_array(INPUT_POST);
     foreach($data as $key => $value) {
         $data->$key = strip_tags($value);
     }
     $emailConfig = $configs['email'];
}
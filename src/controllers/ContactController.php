<?php
$configs = require_once '../../config/config.php';

class ContactController {

    private $requestType;
    private $params;
    private $response = '';

    public function __construct($request)
    {
        $this->requestType = array_shift($request) ?? 'get';
        $this->params = $request;
    }

    public function fulfilRequest()
    {
        switch($this->requestType) {
            case 'get':
                $this->get();
                break;
            case 'post':
                $this->post();
                break;
            case 'put':
                $this->put();
                break;
            case 'delete':
                $this->delete();
                break;
        }
        return $this->response;
    }

    private function get()
    {
        $contact = new Contact();
        switch($this->params[1]) {
            case 'icons':
                $this->response = $contact->getIcons();
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
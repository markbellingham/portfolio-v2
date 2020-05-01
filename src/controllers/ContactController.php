<?php
$configs = require_once '../../config/config.php';

class ContactController {

    private $requestMethod;
    private $params;
    private $response = '';

    /**
     * ContactController constructor.
     * @param array $request
     * @param string|null $requestMethod
     */
    public function __construct(array $request, string $requestMethod = null)
    {
        $this->requestMethod = $requestMethod ?? 'GET';
        $this->params = $request;
    }

    public function fulfilRequest()
    {
        switch($this->requestMethod) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->post();
                break;
            case 'PUT':
                $this->put();
                break;
            case 'DELETE':
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
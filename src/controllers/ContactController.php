<?php
$configs = require_once '../../config/config.php';

class ContactController {

    private string $requestMethod;
    private array $params;
    private array $response = [];

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

    /**
     * @return array
     */
    public function fulfilRequest(): array
    {
        $action = strtolower($this->requestMethod);
        call_user_func(array($this, $action));
        return $this->response;
    }

    private function get()
    {
        $contact = new Contact();
        switch($this->params['endpoint']) {
            case 'icons':
                $this->response['data'] = $contact->getIcons($this->params['ref']);
                break;
        }
    }

    private function post()
    {

    }

    private function put()
    {

    }

    private function delete()
    {

    }
}

if(isset($_POST['send-email'])) {
    $data = filter_input_array(INPUT_POST);
     foreach($data as $key => $value) {
         $data->$key = strip_tags($value);
     }
     $emailConfig = $configs['email'];
}
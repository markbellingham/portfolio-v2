<?php
require_once '../autoload.php';
$frontController = new FrontController();

/**
 * Class FrontController
 * /**
 * API URL format: /api/v{number}/{end-point}/{id}.{format}
 * API example: /api/v1/photo/371.json
 * $request = array(
 *          [0] => string api version ('v1','v2')
 *          [1] => string end point controller ('albums','tracks','playlists','pictures','contact')
 *          [2] => int id
 *      )
 *
 * Supported formats: 'json','xml','csv'
 *
 * REQUEST_METHOD : 'GET' (select), 'POST' (insert), 'PUT' (update), 'DELETE' (delete)
 */
class FrontController
{
    private $requestMethod;
    private $request = [];
    private $response = [];
    private $returnType;

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->returnType = $_GET['type'] ?? '';
        $this->response['data'] = '';
        $this->parseURI();
        $this->callEndpoint();
        $this->echoResponse();
    }

    protected function parseURI()
    {
        switch($this->requestMethod) {
            case 'GET':
                $requestUrl = $_GET['url'] ?? '';
                $requestElements = explode('/', $requestUrl);
                $this->request['api_version'] = $requestElements[0] ?? '';
                $this->request['endpoint'] = $requestElements[1] ?? '';
                $this->request['id'] = $requestElements[2] ?? '';
                $this->request['qty'] = $requestElements[3] ?? '';
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $fn = new Functions();
                parse_str(file_get_contents("php://input"),$request);
                if($fn->requestedByTheSameDomain($request['secret'])) {

                }
                break;
        }
    }

    protected function callEndpoint()
    {
        switch($this->request['endpoint']) {
            case 'albums':
            case 'album':
            case 'tracks':
            case 'track':
            case 'playlist':
                $musicController = new MusicController($this->request, $this->requestMethod);
                $this->response['data'] = $musicController->fulfilRequest();
                break;
            case 'photos':
            case 'photo':
                $picturesController = new PicturesController($this->request, $this->requestMethod);
                $this->response['data'] = $picturesController->fulfilRequest();
                break;
            case 'contact':
                $contactController = new ContactController($this->request, $this->requestMethod);
                $this->response['data'] = $contactController->fulfilRequest();
                break;
            case 'users':
            case 'user':
                $peopleController = new PeopleController($this->request, $this->requestMethod);
                $this->response['data'] = $peopleController->fulfilRequest();
                break;
            case 'lastfm':
                $lastFmController = new LastFmController();
                $lastFmController->refreshData();
                break;
            default:
                header('/');
        }
    }

    protected function echoResponse()
    {
        switch($this->returnType) {
            case 'json':
            case 'datatables':
                echo json_encode($this->response);
                break;
            case 'xml':
                $fn = new Functions();
                $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><rootTag/>');
                $fn->xml_encode($xml, (array) $this->response);
                echo $xml->asXML();
                break;
            case 'csv':
                // echo csv_encode($response['data']);
                break;
            default:
                echo json_encode($this->response);
        }
    }

}

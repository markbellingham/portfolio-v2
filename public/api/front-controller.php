<?php
require_once '../appInit.php';

/**
 * API URL format: /api/v{number}/{end-point}/{id}.{format}
 * API example: /api/v1/photo/371.json
 * $request = array(
 *          [0] => /api/
 *          [1] => string api version ('v1','v2')
 *          [2] => string end point controller ('albums','tracks','playlists','pictures','contact')
 *          [3] => int id
 *      )
 *
 * Supported formats: 'json','xml','csv'
 *
 * REQUEST_METHOD : 'GET' (select), 'POST' (insert), 'PUT' (update), 'DELETE' (delete)
 */

$requestUrl = $_GET['url'] ?? [];
$requestElements = explode('/', $requestUrl);
$request['api_version'] = $requestElements[0] ?? '';
$request['end_point'] = $requestElements[1] ?? '';
$request['id'] = $requestElements[2] ?? '';
$returnType = $_GET['type'] ?? '';
$response = '';
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch($request['end_point']) {
    case 'albums':
    case 'album':
    case 'tracks':
    case 'track':
    case 'playlist':
        $musicController = new MusicController($request, $requestMethod);
        $response = $musicController->fulfilRequest();
        break;
    case 'photos':
    case 'photo':
        $picturesController = new PicturesController($request, $requestMethod);
        $response = $picturesController->fulfilRequest();
        break;
    case 'contact':
        $contactController = new ContactController($request, $requestMethod);
        $response = $contactController->fulfilRequest();
        break;
    case 'users':
    case 'user':
        $peopleController = new PeopleController($request, $requestMethod);
        $response = $peopleController->fulfilRequest();
        break;
    case 'lastfm':
        $lastFmController = new LastFmController();
        $lastFmController->refreshData();
        break;
    default:
        header('/');
}

switch($returnType) {
    case 'json':
        echo json_encode($response);
        break;
    case 'xml':
        $fn = new Functions();
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><rootTag/>');
        $fn->xml_encode($xml, (array) $response);
        echo $xml->asXML();
        break;
    case 'csv':
//        echo csv_encode($response);
        break;
    case 'datatables':
        echo '{"data":' . json_encode($response) . '}';
        break;
    default:
        echo json_encode($response);
}
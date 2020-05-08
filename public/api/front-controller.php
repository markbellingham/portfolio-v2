<?php
require_once '../appInit.php';

/**
 * $request = array(
 *          [0] => /api/
 *          [1] => string api version ('v1','v2')
 *          [2] => string end point controller ('albums','tracks','playlists','pictures','contact')
 *          [3] => int id
 *      )
 */

$requestUrl = $_GET['url'] ?? [];
$request = explode('/', $requestUrl);
$apiVersion = array_shift($request);
$target = $request[0] ?? '';
$returnType = $_GET['type'] ?? '';
$response = '';
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch($target) {
    case 'albums':
    case 'album':
    case 'tracks':
    case 'track':
    case 'playlist':
        $musicController = new MusicController($request, $requestMethod);
        $response = $musicController->fulfilRequest();
        break;
    case 'pictures':
        $picturesController = new PicturesController($request, $requestMethod);
        $response = $picturesController->fulfilRequest();
        break;
    case 'contact':
        $contactController = new ContactController($request, $requestMethod);
        $response = $contactController->fulfilRequest();
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
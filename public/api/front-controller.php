<?php
require_once '../appInit.php';

/**
 * $request = array(
 *          [0] => string api version ('v1','v2')
 *          [1] => string request type ('get','post','put','delete')
 *          [2] => string end point controller ('albums','tracks','playlists','pictures','contact')
 *          [3] => int id
 *          [4] => array values
 *      )
 */

$requestUrl = $_GET['url'] ?? [];
$request = explode('/', $requestUrl);
$apiVersion = array_shift($request);
$target = $request[1] ?? '';
$returnType = $_GET['type'] ?? '';
$response = '';

switch($target) {
    case 'albums':
    case 'album':
    case 'tracks':
    case 'track':
    case 'playlist':
        $musicController = new MusicController($request);
        $response = $musicController->fulfilRequest();
        break;
    case 'pictures':
        $picturesController = new PicturesController($request);
        $response = $picturesController->fulfilRequest();
        break;
    case 'contact':
        $contactController = new ContactController($request);
        $response = $contactController->fulfilRequest();
        break;
    default:
        http_redirect('index.php');
}

switch($returnType) {
    case 'json':
        echo json_encode($response);
        break;
    case 'xml':
//        echo xml_encode($response);
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
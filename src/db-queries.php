<?php
require_once('../src/appInit.php');

use Albums\Albums;

if(isset($_GET['albums'])) {
    $albums = new Albums();
    $data = $albums->findAll();
    $jsonData = '{ "data": '. json_encode($data) .'}';
    echo $jsonData;
}

if(isset($_GET['get-tracks'])) {
    $albums = new Albums();
    $albumId = $_GET['get-tracks'];
    $tracks = $albums->getTracks($albumId);
    echo json_encode($tracks);
}
<?php
namespace Pictures;
require_once(__DIR__."../appInit.php");
use MyPDO\MyPDO;

class Pictures
{
    private $db;
    private $data;

    public function __construct()
    {
        $this->db = MyPDO::instance();
    }



}
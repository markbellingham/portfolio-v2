<?php
namespace Pictures;

use MyPDO\MyPDO;

class Pictures
{
    private $db;
    private $data;

    public function __construct()
    {
        $this->db = MyPDO::instance('Pictures');
    }



}
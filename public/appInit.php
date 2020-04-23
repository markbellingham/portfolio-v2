<?php

spl_autoload_register('\AutoLoader::ControllersLoader');
spl_autoload_register('\AutoLoader::DBLoader');
spl_autoload_register('\AutoLoader::FunctionsLoader');

class AutoLoader
{
    public static function ControllersLoader($className)
    {
        $filename = $_SERVER['DOCUMENT_ROOT'] . '../src/controllers/' . $className . '.php';
        if(file_exists($filename)) {
            include_once $filename;
        }
    }

    public static function DBLoader($className)
    {
        $filename = $_SERVER['DOCUMENT_ROOT'] . '../src/db/' . $className . '.db.php';
        if(file_exists($filename)) {
            include_once $filename;
        }
    }

    public static function FunctionsLoader($className)
    {
        $filename = $_SERVER['DOCUMENT_ROOT'] . '../src/functions/' . $className . '.php';
        if(file_exists($filename)) {
            include_once $filename;
        }
    }
}
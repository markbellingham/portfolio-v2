<?php

spl_autoload_register('\AutoLoader::ClassesLoader');
spl_autoload_register('\AutoLoader::ControllersLoader');
spl_autoload_register('\AutoLoader::DBLoader');
spl_autoload_register('\AutoLoader::FunctionsLoader');
spl_autoload_register('\AutoLoader::ValidatorsLoader');

class AutoLoader
{

    public static function ClassesLoader($className)
    {
        $filename = $_SERVER['DOCUMENT_ROOT'] . '../src/classes/' . $className . '.class.php';
        if(file_exists($filename)) {
            include_once $filename;
        }
    }

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

    public static function ValidatorsLoader($className)
    {
        $filename = $_SERVER['DOCUMENT_ROOT'] . '../src/validators/' . $className . '.php';
        if(file_exists($filename)) {
            include_once $filename;
        }
    }
}
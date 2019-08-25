<?php
namespace MyPDO;
use \PDO;
require_once(__DIR__."/../../config/config.php");

class MyPDO extends PDO
{
    protected static $instance;
    protected $pdo;

    public function __construct()
    {
        $default_options = array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHAR;
        parent::__construct($dsn, DB_USER, DB_PASS, $default_options);
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $default_options);
    }

    // A classical static method to make it universally available
    public static function instance()
    {
        if(self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // A proxy to native PDO methods
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    // A helper function to run prepared statements smoothly
    public function run($sql, $args = NULL)
    {
        if(!$args) {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}
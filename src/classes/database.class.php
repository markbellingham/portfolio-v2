<?php
namespace MyPDO;
use \PDO;
$configs = require_once $_SERVER['DOCUMENT_ROOT'] . "/portfolio-v2/config/config.php";
$dbConfig = $configs['db_music'];

class MyPDO extends PDO
{
    protected static $instance;
    protected $pdo;

    public function __construct()
    {
        global $dbConfig;
        $default_options = array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );
        $dsn = 'mysql:host='.$dbConfig['db_host'].';dbname='.$dbConfig['db_name'].';charset='.$dbConfig['db_char'];
        parent::__construct($dsn, $dbConfig['db_user'], $dbConfig['db_pass'], $default_options);
        $this->pdo = new PDO($dsn, $dbConfig['db_user'], $dbConfig['db_pass'], $default_options);
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
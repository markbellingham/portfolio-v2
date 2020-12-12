<?php
namespace MyPDO;
use \PDO;
use PDOStatement;

class MyPDO extends PDO
{
    protected static array $instance;
    protected PDO $pdo;
    protected bool $error = false;
    protected array $errorInfo;
    protected int $affectedRows = 0;

    public function __construct($db_name)
    {
        $configs = include  $_SERVER['DOCUMENT_ROOT'] . "../config/config.php";
        $dbConfig = $configs[$db_name];
        $default_options = array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );
        $dsn = 'mysql:host='.$dbConfig['db_host'].';dbname='.$dbConfig['db_name'].';charset='.$dbConfig['db_char'];
        parent::__construct($dsn, $dbConfig['db_user'], $dbConfig['db_pass'], $default_options);
        $this->pdo = new PDO($dsn, $dbConfig['db_user'], $dbConfig['db_pass'], $default_options);
    }

    /**
     * A classical static method to make it universally available
     * @param $db_name
     * @return MyPDO
     */
    public static function instance($db_name): MyPDO
    {
        if(!isset(self::$instance[$db_name]) || self::$instance[$db_name] === null) {
            self::$instance[$db_name] = new MyPDO($db_name);
        }
        return self::$instance[$db_name];
    }

    /**
     * A proxy to native PDO methods
     * @param $method
     * @param $args
     * @return false|mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    /**
     * A helper function to run prepared statements smoothly
     * @param $sql
     * @param null $args
     * @return false|PDOStatement
     */
    public function run($sql, $args = NULL)
    {
        $this->error = false;
        if(!$args) {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        if($stmt) {
            $stmt->execute($args);
            $this->affectedRows = $stmt->rowCount();
        } else {
            $this->error = true;
            $this->errorInfo = $stmt->errorInfo();
        }
        return $stmt;
    }

    /**
     * @return bool
     */
    public function errors(): bool
    {
        return $this->error;
    }
}
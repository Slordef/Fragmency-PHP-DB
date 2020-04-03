<?php


namespace Fragmency\Database;


use Fragmency\Core\Application;

class DatabaseManager
{
    private $pdo;

    public function __construct()
    {
        $method = getenv('DATABASE_METHOD');
        $host = getenv('DATABASE_HOST');
        $port = getenv('DATABASE_PORT');
        $user = getenv('DATABASE_USER');
        $pass = getenv('DATABASE_PASS');
        $db = getenv('DATABASE_USE_DB');
        if( $method && $host && $port && $user && $pass && $db ) {
            $this->pdo = new \PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET NAMES utf8");
        }
    }

    public function exec($query){
        if(!$this->pdo) return false;
        echo $query.PHP_EOL;
        $value = $this->pdo->exec($query);
        if($value === false) die(print_r($this->pdo->errorInfo(), true));
        return $value;
    }
    public function query($query,$fetch = true){
        if(!$this->pdo) return false;
        $req = $this->pdo->prepare($query);
        $req->execute();
        if($fetch) return $req->fetchAll();
        return $req;
    }
}
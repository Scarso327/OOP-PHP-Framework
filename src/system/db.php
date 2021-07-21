<?php

namespace System;

use PDOException;

class DB extends \System\Structures\Instance {
    protected static $instance;
    
    private $connection;

    public function __construct()
    {
        try {
            $this->connection = new \PDO(\DB_TYPE . ':host=' . \DB_HOST . ';dbname=' . \DB_DATABASE . ';charset=' . \DB_CHARSET, \DB_USER, \DB_PASS, array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, 
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING
            ));
        } catch (PDOException $e) {
            new \System\Errors\Error("500");
            die();
        }
    }

    private function ExecuteStatement($statement, $params) {
        $q = $this->connection->prepare($statement);
        $q->execute($params);
        
        return $q;
    }

    public function Query($statement, $params = array(), $fetchAll = true) {
        $q = $this->ExecuteStatement("SELECT " . $statement, $params);

        if ($q->rowCount() == 0) return null;

        return ($fetchAll) ? $q->fetchAll() :$q->fetch();
    }

    public function Insert($statement, $params = array()) {
        $q = $this->ExecuteStatement("INSERT INTO " . $statement, $params);
        return ($q->rowCount() == 0) ? null : $this->connection->lastInsertId();
    }

    public function Update($statement, $params = array()) {
        return ($this->ExecuteStatement("UPDATE " . $statement, $params)->rowCount() > 0);
    }

    public function Delete($table, $condition, $params = array()) {
        return ($this->ExecuteStatement("UPDATE " . $table . " SET active = '0' WHERE ".$condition, $params)->rowCount() > 0);
    }
}
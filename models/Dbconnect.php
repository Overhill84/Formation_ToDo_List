<?php

namespace Models;

abstract class DbConnect implements Crud {

    protected $pdo;
    protected $id;

    function __construct($id = null) {
        $this->pdo = new \PDO(DATABASE, LOGIN, PASSWD);
        $this->id = $id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getId(): int {
        return $this->id;
    }

    abstract function insert();
    abstract function delete();
    abstract function update();
    abstract function selectAll();
    abstract function select();

}
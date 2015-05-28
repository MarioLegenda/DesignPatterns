<?php

include_once('DomainModel.php');

class DatabaseDummy
{
}

abstract class Mapper
{
    protected static $PDO;

    abstract function update(DomainObject $object );
    protected abstract function doCreateObject( array $array );
    protected abstract function doInsert(DomainObject $object );
    protected abstract function selectStmt();

    public function __construct() {
        if (!isset(self::$PDO)) {
            self::$PDO = new DatabaseDummy();
        }
    }

    function find($id) {
        $this->selectStmt()->execute(array($id));
        $array = $this->selectStmt()->fetch();
        $this->selectStmt()->closeCursor();
        if (!is_array($array)) {
            return null;
        }
        if (!isset($array['id'])) {
            return null;
        }
        $object = $this->createObject($array);
        return $object;
    }

    function createObject($array) {
        $obj = $this->doCreateObject($array);
        return $obj;
    }

    function insert(DomainObject $obj) {
        $this->doInsert($obj);
    }
}

class UserMapper extends Mapper
{
    private $selectStmt;
    private $updateStmt;
    private $insertStmt;

    function __construct() {
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM venue WHERE id=?");
        $this->updateStmt = self::$PDO->prepare("update venue set name=?, id=? where id=?");
        $this->insertStmt = self::$PDO->prepare("insert into venue ( name ) values( ? )");
    }

    function getCollection(array $raw) {
        return new SpaceCollection($raw, $this);
    }

    protected function doCreateObject(array $array) {
        $obj = new User($array['id']);
        $obj->setname($array['name']);
        return $obj;
    }

    protected function doInsert(DomainObject $object) {
        print "inserting\n";
        debug_print_backtrace();
        $values = array($object->getName());
        $this->insertStmt->execute($values);
        $id = self::$PDO->lastInsertId();
        $object->setId($id);
    }

    function update(DomainObject $object) {
        print "updating\n";
        $values = array($object->getName(), $object->getId(), $object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt() {
        return $this->selectStmt;
    }
}
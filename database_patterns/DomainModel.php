<?php

abstract class DomainObject
{
    private $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getId($id) {
        return $this->id;
    }

    public static function getCollection($type) {
        return array(); // ne vraša za sada ništa
    }

    public function collection() {
        return self::getCollection(get_class($this));
    }
}

class User extends DomainObject
{
    private $name;
    private $surname;
    private $pass;
    private $birth;
    private $registered;
    private $comments;

    public function __construct($id) {
        $this->comments = self::getCollection("Comments");
        parent::__construct($id);
    }

    public function setComments($for_id) {
        // neodređeno što radi
    }

    public function getComments() {
        $this->comments;
    }
}
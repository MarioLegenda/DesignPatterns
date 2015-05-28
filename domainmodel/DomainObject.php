<?php

abstract class DomainObject
{
    private $id;

    public function __construct($id=null) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public static function getCollection($type) {
        return array(); // kasnije impelementirati
    }

    public function collection() {
        return self::getCollection( get_class($this) );
    }
}
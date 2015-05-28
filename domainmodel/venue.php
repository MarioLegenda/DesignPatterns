<?php

require 'domainobject.php';

class Venue extends DomainObject
{
    private $name;
    private $spaces;

    public function __construct($id = null, $name = null) {
        $this->name = $name;
        $this->spaces = self::getCollection('Space');
        parent::__construct($id);
    }

    public function setSpaces(SpaceCollection $spaces) {
        $this->spaces = $spaces;
    }

    public function getSpaces() {
        $this->spaces;
    }
}
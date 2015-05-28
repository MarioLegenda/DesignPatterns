<?php

abstract class Unit
{
    protected $units = array();

    abstract public function addUnit(Unit $unit);
}

class
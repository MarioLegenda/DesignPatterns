<?php

namespace UnitOfWork\Model;

interface EntityInterface
{
    public function setField($name, $value);
    public function getField($name);
    public function fieldExists($name);
    public function removeField($name);
    public function toArray();
}
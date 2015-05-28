<?php

namespace UnitOfWork\Mapper;

## primjeniti kasnije
//use ModelUser;

/*
    UserMapper nasljeđuje konstruktor AbstractDataMapper odnosno klase od koje nasljeđuje.
    AbstractDataMapper je abstraktna klasa.
*/

class UserMapper extends AbstractDataMapper
{
    protected $entityTable = "users";

    protected function loadEntity(array $row) {
        return new User(array(
            "id"    => $row["id"],
            "name"  => $row["name"],
            "email" => $row["email"],
            "role"  => $row["role"]));
    }
}
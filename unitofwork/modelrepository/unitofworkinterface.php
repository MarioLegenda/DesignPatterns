<?php

namespace UnitOfWork\ModelRepository;

##  dodati kasnije
//use ModelEntityInterface;

interface UnitOfWorkInterface
{
    public function fetchById($id);
    public function registerNew(EntityInterface $entity);
    public function registerClean(EntityInterface $entity);
    public function registerDirty(EntityInterface $entity);
    public function registerDeleted(EntityInterface $entity);
    public function commit();
    public function rollback();
    public function clear();
}
<?php

namespace UnitOfWork\Mapper;

use UnitOfWork\ModelCollection\EntityCollectionInterface;
use UnitOfWork\Model\ModelEntityInterface;

abstract class AbstractDataMapper implements DataMapperInterface
{
    protected $adapter;
    protected $collection;
    protected $entityTable;

    /*
     *     Ova klasa odnosno njen konstruktor se upotrebljava odmah na početku, odnosno u dijelu koji inicijalizira UnitOfWork objekt.
     *     npr. $unitOfWork = new UnitOfWork(new UserMapper($adapter, new EntityCollection), new ObjectStorage);
     *     On sprema PDO objekt u $this->adapter te EntityCollection objekt u $this->collection.
    */

    public function __construct(DatabaseAdapterInterface $adapter, EntityCollectionInterface $collection, $entityTable = null) {
        $this->adapter = $adapter;
        $this->collection = $collection;
        if ($entityTable !== null) {
            $this->setEntityTable($entityTable);
        }
    }

    public function setEntityTable($entityTable) {
        if (!is_string($table) || empty($entityTable)) {
            throw new InvalidArgumentException("The entity table is invalid.");
        }

        $this->entityTable = $entityTable;
        return $this;
    }

    public function fetchById($id) {
        $this->adapter->select($this->entityTable,
            array("id" => $id));
        // fetch() metoda nije dio ovog sučelja. To je metoda PDO objekta. Ako nema podataka, vraća null. Ako ima, stvara se novi User
        // objekt pomoću metode loadEntity() sa podacima prikupljenim sa baze. loadEntity() metoda je u biti Mapper pattern koji se sinkronizira
        // sa DomainModel patternom te obavlja sva CRUD djelatnosti vezane sa bazom.
        if (!$row = $this->adapter->fetch()) {
            return null;
        }
        return $this->loadEntity($row);
    }

    public function fetchAll(array $conditions = array()) {
        $this->adapter->select($this->entityTable, $conditions);
        $rows = $this->adapter->fetchAll();
        return $this->loadEntityCollection($rows);
    }

    public function insert(EntityInterface $entity) {
        return $this->adapter->insert($this->entityTable,
            $entity->toArray());
    }

    public function update(EntityInterface $entity) {
        return $this->adapter->update($this->entityTable,
            $entity->toArray(), "id = $entity->id");
    }

    public function save(EntityInterface $entity) {
        return !isset($entity->id)
            ? $this->adapter->insert($this->entityTable,
                $entity->toArray())
            : $this->adapter->update($this->entityTable,
                $entity->toArray(), "id = $entity->id");
    }

    public function delete(EntityInterface $entity) {
        return $this->adapter->delete($this->entityTable,
            "id = $entity->id");
    }

    protected function loadEntityCollection(array $rows) {
        $this->collection->clear();
        foreach ($rows as $row) {
            $this->collection[] = $this->loadEntity($row);
        }
        return $this->collection;
    }

    abstract protected function loadEntity(array $row);
}
<?php

namespace Lumie\WarehouseManagerApplication\Repository;

use Lumie\WarehouseManagerApplication\Entity\IdentifiableEntityInterface;

class Repository implements RepositoryInterface
{
    protected $data;

    protected $currentId = 1;

    public function __construct(
        protected array $supportedTypes
    ) {
    }

    public function insert(IdentifiableEntityInterface $entity)
    {
        $this->checkSupported($entity);

        $this->data[$entity->getId()] = $entity;

        $this->currentId = max($this->currentId, $entity->getId());
        $this->bumpId();
    }

    public function get(int $id)
    {
        return $this->data[$id] ?? null;
    }

    public function removeById(int $id)
    {
        if (array_key_exists($id, $this->data)) {
            unset($this->data[$id]);
        }
    }

    public function remove(IdentifiableEntityInterface $entity)
    {
        $this->checkSupported($entity);

        $this->removeById($entity->getId());
    }

    public function all()
    {
        return $this->data;
    }

    public function clear()
    {
        $this->data = [];
    }

    public function getCurrentId()
    {
        $id = $this->currentId;
        $this->bumpId();

        return $id;
    }

    private function bumpId()
    {
        $this->currentId++;
    }

    private function checkSupported(IdentifiableEntityInterface $entity)
    {
        if (!in_array(get_class($entity), $this->supportedTypes)) {
            throw new \Exception("Unsupported entity type");
        }
    }
}

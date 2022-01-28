<?php

namespace Lumie\WarehouseManagerApplication\Service;

use Lumie\WarehouseManagerApplication\Entity\IdentifiableEntityInterface;

interface EntityManagerInterface
{
    public function get(string $class, int $id);

    public function getAll(string $class);

    public function persist(IdentifiableEntityInterface $entity);

    public function isManaged(IdentifiableEntityInterface $entity);

    public function isSupported($entityOrClass);

    public function create(string $class);

    public function clear();
}

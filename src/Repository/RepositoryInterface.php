<?php

namespace Lumie\WarehouseManagerApplication\Repository;

use Lumie\WarehouseManagerApplication\Entity\IdentifiableEntityInterface;

interface RepositoryInterface
{
    public function insert(IdentifiableEntityInterface $entity);

    public function get(int $id);

    public function remove(IdentifiableEntityInterface $entity);

    public function removeById(int $id);

    public function all();

    public function clear();

    public function getCurrentId();
}

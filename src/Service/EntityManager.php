<?php

namespace Lumie\WarehouseManagerApplication\Service;

use Lumie\WarehouseManagerApplication\Entity\IdentifiableEntityInterface;
use Lumie\WarehouseManagerApplication\Repository\Repository;
use Lumie\WarehouseManagerApplication\Repository\RepositoryInterface;

class EntityManager implements EntityManagerInterface
{

    /** @var RepositoryInterface[] $data */
    protected $data = [];

    public function __construct(
        protected array $mapping
    ) {
        $this->initRepositories();
    }

    public function get(string $class, $id)
    {
        return $this->fetchEntities($class, $id)[0] ?? null;
    }

    public function getAll(string $class)
    {
        return $this->fetchEntities($class);
    }

    public function clear()
    {
        foreach ($this->data as $repository) {
            $repository->clear();
        }
    }

    public function persist(IdentifiableEntityInterface $entity)
    {
        if (!$this->isSupported($entity)) {
            throw new \Exception('Unsupported entity class: ' . get_class($entity));
        }

        if (!$this->isManaged($entity)) {
            $this->data[$this->getMappedClass($entity)]->insert($entity);
        }
    }

    public function isManaged(IdentifiableEntityInterface $entity)
    {
        return $this->has($this->getMappedClass($entity), $entity->getId());
    }

    public function isSupported($entityOrClass)
    {
        return $this->getMappedClass($entityOrClass) !== null;
    }

    public function create(string $class)
    {
        if (!$this->isSupported($class)) {
            throw new \Exception('Unsupported entity class: ' . $class);
        }

        $id = $this->data[$this->getMappedClass($class)]->getCurrentId();
        $entity = new $class($id);

        return $entity;
    }

    protected function has(string $class, string|int $id, $strict = true)
    {
        $mappedClass = $this->getMappedClass($class);
        if (!$mappedClass || !array_key_exists($mappedClass, $this->data)) {
            return false;
        }

        $entity = $this->data[$mappedClass]->get($id);
        if (!$entity || ($strict && $class != get_class($entity))) //Return false if entity class doesn't match actual class
        {
            return false;
        }

        return true;
    }

    protected function fetchEntities(string $class, int $id = null)
    {
        $mappedClass = $this->getMappedClass($class);

        if (!$mappedClass) {
            throw new \Exception("Unsupported class $class");
        }

        if ($id) {
            $entity = $this->data[$mappedClass]->get($id);
            return $entity ? [$entity] : [];
        } else {
            $entities = $this->data[$mappedClass]->all();
            if ($mappedClass != $class) {
                $entities = array_filter($entities, function ($item) use ($class) {
                    return get_class($item) == $class;
                });
            }

            return $entities;
        }
    }

    private function getMappedClass($entityOrClass)
    {
        if ($entityOrClass instanceof IdentifiableEntityInterface) {
            $entityOrClass = get_class($entityOrClass);
        }

        return $this->mapping[$entityOrClass] ?? null;
    }

    protected function initRepositories()
    {
        $reverseMapping = [];
        foreach ($this->mapping as $class => $mappedClass) {
            $reverseMapping[$mappedClass][] = $class;
        }

        foreach ($reverseMapping as $mappedClass => $classes) {
            $this->data[$mappedClass] = new Repository($classes);
        }
    }
}

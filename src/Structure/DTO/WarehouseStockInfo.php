<?php

namespace Lumie\WarehouseManagerApplication\Structure\DTO;

use Lumie\WarehouseManagerApplication\Entity\Product\AbstractProduct;
use Lumie\WarehouseManagerApplication\Entity\Warehouse;
use Lumie\WarehouseManagerApplication\Structure\Entity\WarehouseStock;

class WarehouseStockInfo
{
    protected $id;
    protected $name;
    protected $capacity;
    protected $freeSpace;

    /** @var WarehouseStock[] $products */
    protected $products = [];

    public function __construct(Warehouse $warehouse)
    {
        $this->id = $warehouse->getId();
        $this->name = $warehouse->getName();
        $this->capacity = $warehouse->getCapacity();
        $this->freeSpace = $warehouse->getFreeSpace();
        foreach ($warehouse->getProducts() as $warehouseStock) {
            $this->products[$warehouseStock->getProduct()->getId()] = $warehouseStock;
        }
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of capacity
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Get the value of freeSpace
     */
    public function getFreeSpace()
    {
        return $this->freeSpace;
    }

    /**
     * Get the value of products
     * @return WarehouseStock[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}

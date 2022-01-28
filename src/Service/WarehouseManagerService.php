<?php

namespace Lumie\WarehouseManagerApplication\Service;

use Lumie\WarehouseManagerApplication\Entity\Brand;
use Lumie\WarehouseManagerApplication\Entity\Product\AbstractProduct;
use Lumie\WarehouseManagerApplication\Entity\Warehouse;
use Lumie\WarehouseManagerApplication\Structure\DTO\Warehouse as DTOWarehouse;
use Lumie\WarehouseManagerApplication\Structure\DTO\WarehouseStockInfo;
use Lumie\WarehouseManagerApplication\Util\Util;

class WarehouseManagerService
{
    public function __construct(
        protected EntityManager $em
    ) {
    }

    public function addWarehouse(DTOWarehouse $warehouse)
    {
        /** @var Warehouse $warehouseEntity */
        $warehouseEntity = $this->em->create(Warehouse::class);

        $warehouseEntity
            ->setName($warehouse->getName())
            ->setAddress($warehouse->getAddress())
            ->setCapacity($warehouse->getCapacity());

        $this->em->persist($warehouseEntity);
    }

    public function getProducts()
    {
        $products = $this->em->getAll(AbstractProduct::class);

        return $products;
    }

    /**
     * @param AbstractProduct $product
     * @param int $quantity
     * @param Warehouse[]|null $warehouses
     */
    public function modifyWarehouseProductStock($productId, int $quantity, array $warehouses = [])
    {
        if (Util::isArrayType('int', $warehouses)) {
            $warehouses = array_map(function ($id) {
                return $this->em->get(Warehouse::class, $id);
            }, $warehouses);
        }

        if (!Util::isArrayType(Warehouse::class, $warehouses)) {
            throw new \Exception("Warehouse array supplied contains invalid values");
        }

        $product = $this->em->get(AbstractProduct::class, $productId);

        if (empty($warehouses)) {
            $warehouses = $this->em->getAll(Warehouse::class);
        }

        $isRemove = $quantity < 0;
        $quantity = abs($quantity);

        reset($warehouses);
        $modifications = [];

        /** @var Warehouse[] $warehouses */
        while ($quantity > 0 && current($warehouses) != null) {
            $warehouse = current($warehouses);

            $subQuantity = $isRemove
                ? min($quantity, $warehouse->getProductQuantity($product))
                : min($quantity, $warehouse->getFreeSpace());

            $modifications[] = [$warehouse, $subQuantity];

            $quantity -= $subQuantity;
            next($warehouses);
        }

        if ($quantity > 0) {
            throw new \Exception("Failed to modify stock: " . ($isRemove ? "Not enough stock" : "Not enough space"));
        } else {
            foreach ($modifications as list($warehouse, $quantity)) {
                if ($isRemove) {
                    $warehouse->removeProduct($product, $quantity);
                } else {
                    $warehouse->addProduct($product, $quantity);
                }
            }
        }
    }

    public function getWarehouseStockInfo(int $id): WarehouseStockInfo
    {
        /** @var Warehouse $warehouse */
        $warehouse = $this->em->get(Warehouse::class, $id);

        return new WarehouseStockInfo($warehouse);
    }

    public function getAllStockInfo()
    {
        $stockinfoArray = [];
        /** @var Warehouse $warehouse */
        foreach ($this->em->getAll(Warehouse::class) as $warehouse) {
            $stockinfoArray[] = new WarehouseStockInfo($warehouse);
        }

        return $stockinfoArray;
    }
}

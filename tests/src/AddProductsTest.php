<?php

namespace Lumie\WarehouseManagerApplicationTest;

use Lumie\WarehouseManagerApplication\Service\WarehouseManagerService;
use Lumie\WarehouseManagerApplication\Structure\DTO\Warehouse;
use Lumie\WarehouseManagerApplicationTest\Traits\KernelAwareTrait;
use PHPUnit\Framework\TestCase;

class AddProductsTest extends TestCase
{
    use KernelAwareTrait;

    public function testAddProductToOneWarehouse(): void
    {
        $this->boot();
        
        /** @var WarehouseManagerService $service */
        $service = $this->kernel->get(WarehouseManagerService::class);

        $warehouseStocks = $service->getWarehouseStockInfo(1)->getProducts();
        $stock = array_shift($warehouseStocks);

        $quantity = $stock->getQuantity();

        $service->modifyWarehouseProductStock($stock->getProduct()->getId(), 1, [1]);

        $warehouseStocks = $service->getWarehouseStockInfo(1)->getProducts();
        $stock = array_shift($warehouseStocks);

        $newQuantity = $stock->getQuantity();

        $this->assertEquals($quantity+1, $newQuantity);
    }
    
    public function testAddProductToMultipleWarehouses(): void
    {
        $this->boot();
        
        /** @var WarehouseManagerService $service */
        $service = $this->kernel->get(WarehouseManagerService::class);

        $warehouseStockInfo = $service->getWarehouseStockInfo(1);
        $free = $warehouseStockInfo->getFreeSpace();

        $secondWarehouseStockInfo = $service->getWarehouseStockInfo(2);
        $secondFree = $secondWarehouseStockInfo->getFreeSpace();

        $service->modifyWarehouseProductStock(1, $free+1, [1,2]);
        
        $warehouseStockInfo = $service->getWarehouseStockInfo(1);
        $freeAfter = $warehouseStockInfo->getFreeSpace();

        $secondWarehouseStockInfo = $service->getWarehouseStockInfo(2);
        $secondFreeAfter = $secondWarehouseStockInfo->getFreeSpace();

        $this->assertEquals($freeAfter, 0);
        $this->assertEquals($secondFreeAfter, $secondFree-1);
    }

    public function testAddProductOutofSpace() : void
    {
        $this->boot();

        $this->expectExceptionMessage('Failed to modify stock: Not enough space');
        
        /** @var WarehouseManagerService $service */
        $service = $this->kernel->get(WarehouseManagerService::class);

        $service->modifyWarehouseProductStock(1, 1000000);
    }
}
<?php

namespace Lumie\WarehouseManagerApplicationTest;

use Lumie\WarehouseManagerApplication\Service\WarehouseManagerService;
use Lumie\WarehouseManagerApplication\Structure\DTO\Warehouse;
use Lumie\WarehouseManagerApplicationTest\Traits\KernelAwareTrait;
use PHPUnit\Framework\TestCase;

class RemoveProductsTest extends TestCase
{
    use KernelAwareTrait;

    public function testRemoveProductFromOneWarehouse(): void
    {
        $this->boot();
        
        /** @var WarehouseManagerService $service */
        $service = $this->kernel->get(WarehouseManagerService::class);

        $warehouseStocks = $service->getWarehouseStockInfo(1)->getProducts();
        $stock = array_shift($warehouseStocks);

        $quantity = $stock->getQuantity();

        $service->modifyWarehouseProductStock($stock->getProduct()->getId(), -1, [1]);

        $warehouseStocks = $service->getWarehouseStockInfo(1)->getProducts();
        $stock = array_shift($warehouseStocks);

        $newQuantity = $stock?->getQuantity() ?? 0;

        $this->assertEquals($quantity-1, $newQuantity);
    }
    
    public function testRemoveProductFromMultipleWarehouses(): void
    {
        $this->boot();
        
        /** @var WarehouseManagerService $service */
        $service = $this->kernel->get(WarehouseManagerService::class);

        $warehouseStockInfo = $service->getWarehouseStockInfo(1);
        $stockSize = $warehouseStockInfo->getProducts()[1]->getQuantity();

        $secondWarehouseStockInfo = $service->getWarehouseStockInfo(3);
        $secondStockSize = $warehouseStockInfo->getProducts()[1]->getQuantity();

        $service->modifyWarehouseProductStock(1, -($stockSize+1), [1,3]);
        
        $warehouseStockInfo = $service->getWarehouseStockInfo(1);
        $stockSizeAfter = ($warehouseStockInfo->getProducts()[1] ?? null)?->getQuantity() ?? 0;

        $secondWarehouseStockInfo = $service->getWarehouseStockInfo(2);
        $secondStockSizeAfter = ($secondWarehouseStockInfo->getProducts()[1] ?? null)?->getQuantity() ?? 0;

        $this->assertEquals(0, $stockSizeAfter);
        $this->assertEquals($secondStockSizeAfter, $secondStockSize-1);
    }
    

    public function testRemoveProductOutofStock() : void
    {
        $this->boot();

        $this->expectExceptionMessage('Failed to modify stock: Not enough stock');
        
        /** @var WarehouseManagerService $service */
        $service = $this->kernel->get(WarehouseManagerService::class);

        $service->modifyWarehouseProductStock(1, -1000000);
    }
    
}
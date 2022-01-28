<?php

namespace Lumie\WarehouseManagerApplicationTest;

use Lumie\WarehouseManagerApplication\Entity\Warehouse as EntityWarehouse;
use Lumie\WarehouseManagerApplication\Service\EntityManagerInterface;
use Lumie\WarehouseManagerApplication\Service\WarehouseManagerService;
use Lumie\WarehouseManagerApplication\Structure\DTO\Warehouse;
use Lumie\WarehouseManagerApplicationTest\Traits\KernelAwareTrait;
use PHPUnit\Framework\TestCase;

class CreateWarehouseTest extends TestCase
{
    use KernelAwareTrait;

    public function testCreateWarehouse(): void
    {
        $this->boot();

        $warehouseDTO = new Warehouse();
        $warehouseDTO
            ->setName("Test warehouse")
            ->setAddress("Test address")
            ->setCapacity(10)
        ;
        
        $this->kernel->get(WarehouseManagerService::class)->addWarehouse($warehouseDTO);

        $this->assertEquals(count($this->kernel->get(EntityManagerInterface::class)->getAll(EntityWarehouse::class)), 4);
    }

    public function testCreateWarehouseZeroCapacity(): void
    {
        $this->boot();

        $this->expectExceptionMessage("Capacity must be positive");

        $warehouseDTO = new Warehouse();
        $warehouseDTO
            ->setName("Test warehouse")
            ->setAddress("Test address")
            ->setCapacity(0)
        ;
        
        $this->kernel->get(WarehouseManagerService::class)->addWarehouse($warehouseDTO);        
    }
    
    public function testCreateWarehouseNegativeCapacity(): void
    {
        $this->boot();

        $this->expectExceptionMessage("Capacity must be positive");

        $warehouseDTO = new Warehouse();
        $warehouseDTO
            ->setName("Test warehouse")
            ->setAddress("Test address")
            ->setCapacity(-1)
        ;
        
        $this->kernel->get(WarehouseManagerService::class)->addWarehouse($warehouseDTO);        
    }
    
}
<?php

namespace Lumie\WarehouseManagerApplication\Structure\Definition;

use Lumie\WarehouseManagerApplication\Entity\Brand;
use Lumie\WarehouseManagerApplication\Entity\Product\AbstractProduct;
use Lumie\WarehouseManagerApplication\Entity\Product\Headphone;
use Lumie\WarehouseManagerApplication\Entity\Product\Keyboard;
use Lumie\WarehouseManagerApplication\Entity\Warehouse;

class ClassMappingDefinition
{
    public static function getClassMapping()
    {
        return [
            Warehouse::class => Warehouse::class,
            Brand::class => Brand::class,
            Keyboard::class => AbstractProduct::class,
            Headphone::class => AbstractProduct::class,
            AbstractProduct::class => AbstractProduct::class
        ];
    }
}

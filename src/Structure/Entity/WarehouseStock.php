<?php

namespace Lumie\WarehouseManagerApplication\Structure\Entity;

use Lumie\WarehouseManagerApplication\Entity\Product\AbstractProduct;

class WarehouseStock
{
    public function __construct(
        protected AbstractProduct $product,
        protected int $quantity
    ) {
    }

    /**
     * Get the value of product
     */
    public function getProduct(): AbstractProduct
    {
        return $this->product;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}

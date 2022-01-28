<?php

namespace Lumie\WarehouseManagerApplication\Entity;

use Lumie\WarehouseManagerApplication\Entity\Product\AbstractProduct;
use Lumie\WarehouseManagerApplication\Structure\Entity\WarehouseStock;

class Warehouse extends AbstractEntity
{
    protected $name;

    protected $address;

    protected $capacity;

    /** @var WarehouseStock[] $products */
    protected $products = [];

    public function getFreeSpace(): int
    {
        return $this->capacity - array_reduce($this->products, function ($carry, WarehouseStock $item) {
            return $carry + $item->getQuantity();
        }, 0);
    }

    /**
     * Get the value of products
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Get the value of products
     */
    public function getProductQuantity(AbstractProduct $product)
    {
        return ($this->products[$product->getId()] ?? null)?->getQuantity() ?? 0;
    }


    /**
     * Add product
     */
    public function addProduct(AbstractProduct $product, int $quantity): self
    {
        if ($quantity < 0) {
            throw new \Exception("Negative value for quantity");
        }

        if ($this->getFreeSpace() < $quantity) {
            throw new \Exception("Stock does not fit");
        }

        if (array_key_exists($product->getId(), $this->products)) {
            $quantity += $this->products[$product->getId()]->getQuantity();
        }

        $this->products[$product->getId()] = new WarehouseStock($product, $quantity);

        return $this;
    }

    /**
     * Remove product
     */
    public function removeProduct(AbstractProduct $product, int $quantity): self
    {
        $productId = $product->getId();

        if ($quantity < 0) {
            throw new \Exception("Negative value for quantity");
        }

        if (!array_key_exists($productId, $this->products) || $quantity > $this->products[$productId]->getQuantity()) {
            throw new \Exception("Not enough products in warehouse");
        }

        $newQuantity = $this->products[$productId]->getQuantity() - $quantity;

        if ($newQuantity == 0) {
            unset($this->products[$productId]);
        } else {
            $this->products[$productId] = new WarehouseStock($product, $newQuantity);
        }

        return $this;
    }

    /**
     * Get the value of capacity
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set the value of capacity
     */
    public function setCapacity($capacity): self
    {
        if ($capacity <= 0) {
            throw new \Exception("Capacity must be positive");
        }
        if ($capacity < $this->capacity - $this->getFreeSpace()) {
            throw new \Exception("Cannot set capacity lower than current stock size");
        }

        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get the value of address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     */
    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }
}

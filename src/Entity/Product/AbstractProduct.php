<?php

namespace Lumie\WarehouseManagerApplication\Entity\Product;

use Lumie\WarehouseManagerApplication\Entity\AbstractEntity;
use Lumie\WarehouseManagerApplication\Entity\Brand;

abstract class AbstractProduct extends AbstractEntity
{
    protected ?string $productNumber;

    protected ?string $name;

    protected ?int $price;

    protected ?Brand $brand;

    /**
     * Get the value of productNumber
     *
     * @return string
     */
    public function getProductNumber(): ?string
    {
        return $this->productNumber;
    }

    /**
     * Set the value of productNumber
     *
     * @param string $productNumber
     *
     * @return self
     */
    public function setProductNumber(string $productNumber): self
    {
        $this->productNumber = $productNumber;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of price
     *
     * @return int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param int $price
     *
     * @return self
     */
    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of brand
     *
     * @return Brand
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @param Brand $brand
     *
     * @return self
     */
    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}

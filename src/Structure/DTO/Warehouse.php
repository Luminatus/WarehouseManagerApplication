<?php

namespace Lumie\WarehouseManagerApplication\Structure\DTO;

class Warehouse
{
    protected string $name;
    protected string $address;
    protected string $capacity;

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
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
     * Get the value of address
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param string $address
     *
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of capacity
     *
     * @return string
     */
    public function getCapacity(): string
    {
        return $this->capacity;
    }

    /**
     * Set the value of capacity
     *
     * @param string $capacity
     *
     * @return self
     */
    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }
}

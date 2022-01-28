<?php

namespace Lumie\WarehouseManagerApplication\Entity;

class Brand extends AbstractEntity
{
    protected string $name;

    protected int $qualityRating;

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
     * Get the value of qualityRating
     *
     * @return int
     */
    public function getQualityRating(): int
    {
        return $this->qualityRating;
    }

    /**
     * Set the value of qualityRating
     *
     * @param int $qualityRating
     *
     * @return self
     */
    public function setQualityRating(int $qualityRating): self
    {
        $this->qualityRating = $qualityRating;

        return $this;
    }
}

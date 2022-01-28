<?php

namespace Lumie\WarehouseManagerApplication\Entity;

abstract class AbstractEntity implements IdentifiableEntityInterface
{
    public function __construct(
        protected $id
    ) {
    }

    public function getId()
    {
        return $this->id;
    }
}

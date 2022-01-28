<?php

namespace Lumie\WarehouseManagerApplication\Service;

interface DataProviderInterface
{
    public function load(bool $force = false);
}

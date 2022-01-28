<?php

namespace Lumie\WarehouseManagerApplicationTest\Traits;

use Lumie\WarehouseManagerApplication\Kernel;

trait KernelAwareTrait
{
    
    protected $kernel;

    private function boot()
    {
        $kernel = new Kernel();
        $kernel->boot();

        $this->kernel = $kernel;
    }
}
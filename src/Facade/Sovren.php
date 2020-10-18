<?php

namespace Siwei\SovrenParser\Facade;

use Illuminate\Support\Facades\Facade;

class Sovren extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sovren-parser';
    }
}

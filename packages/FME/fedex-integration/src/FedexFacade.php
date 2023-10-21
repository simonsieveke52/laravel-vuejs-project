<?php

namespace FME\Fedex;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FME\Fedex\FedexRepository
 */
class FedexFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'FedexRepository';
    }
}

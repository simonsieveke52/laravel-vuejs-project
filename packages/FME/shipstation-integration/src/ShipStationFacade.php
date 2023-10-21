<?php

namespace FME\ShipStation;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FME\ShipStation\ShipStationRepository
 */
class ShipStationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ShipStationRepository';
    }
}

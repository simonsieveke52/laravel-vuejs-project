<?php

namespace App\Http\Controllers\API;

use App\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CitiesResources;
use App\Http\Resources\StatesResources;

class StateController extends Controller
{
    /**
     * Get all available states
     *
     * @param  int $countryId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(int $countryId)
    {
        return StatesResources::collection(
            State::where('country_id', $countryId)->orderBy('name', 'asc')->get()
        );
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackingNumbersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($request) {
            default:
                return [
                    'name' => $this->name,
                    'number' => $this->number,
                    'created_at' => $this->created_at->format('m/d/Y h:ia'),
                ];
        }
    }
}

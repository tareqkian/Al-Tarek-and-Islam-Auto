<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MenuItemResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /* return parent::toArray($request); */
        return [
            "id" => $this->id,
            "name" => $this->name,
            "importedComponent" => $this->importedComponent,
            "items" => MenuItemResource::collection($this->whenLoaded('parentItems')),
            "items" => MenuItemResource::collection($this->whenLoaded('items'))
        ];
    }
}

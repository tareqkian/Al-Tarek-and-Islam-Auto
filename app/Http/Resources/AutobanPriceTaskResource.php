<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AutobanPriceTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'duration' => $this->duration,
          'brand' => new AutobanBrandResource($this->whenLoaded('brand')),
          'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}

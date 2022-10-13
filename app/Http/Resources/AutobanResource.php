<?php

namespace App\Http\Resources;

use App\Models\AutobanType;
use Illuminate\Http\Resources\Json\JsonResource;

class AutobanResource extends JsonResource
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
      'model' => new AutobanModelResource($this->whenLoaded('model')),
      'type' => new AutobanTypeResource($this->whenLoaded('type')),
      'year' => new AutobanYearResource($this->whenLoaded('year')),
      'price' => new AutobanPriceResource($this->whenLoaded('price')),
      'price_list_appearance' => ($this->price_list_appearance || false),
      'market_availability' => ($this->market_availability || false),
      'order' => $this->order,
      'pivots' => AutobanCategoryResource::collection($this->whenLoaded('pivots'))
    ];
  }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AutobanCategoryResource extends JsonResource
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
      'category' => new OptionCategoryResource($this->whenLoaded('category')),
      'options' => OptionResource::collection($this->whenLoaded('options')),
      'option' => $this->option
    ];
  }
}

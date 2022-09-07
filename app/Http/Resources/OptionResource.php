<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
      'option_category_id' => $this->option_category_id,
      'option_category' => new OptionCategoryResource($this->whenLoaded('option_category')),
      'option_title' => $this->option_title,
      'translations' => TranslationsResource::collection($this->whenLoaded('translations'))->relationName('option'),
      'abbreviation' => $this->abbreviation,
      'order' => $this->order
    ];
  }
}

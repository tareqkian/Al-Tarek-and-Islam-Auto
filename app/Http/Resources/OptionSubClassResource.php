<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionSubClassResource extends JsonResource
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
      'option_class_id' => $this->option_class_id,
      'option_sub_class_title' => $this->option_sub_class_title,
      'translations' => TranslationsResource::collection($this->whenLoaded('translations'))->relationName('option_sub_class'),
      'option_class' => new OptionClassResource($this->whenLoaded('option_class')),
      'option_categories' => OptionCategoryResource::collection($this->whenLoaded('option_categories')),
      'children' => OptionCategoryResource::collection($this->whenLoaded('children')),
      'order' => $this->order
    ];
  }
}

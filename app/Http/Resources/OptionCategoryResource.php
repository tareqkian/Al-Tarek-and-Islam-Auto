<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionCategoryResource extends JsonResource
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
      'option_sub_class_id' => $this->option_sub_class_id,
      'option_sub_class' => new OptionSubClassResource($this->whenLoaded('option_sub_class')),
      'options' => OptionResource::collection($this->whenLoaded('options')),
      'children' => OptionResource::collection($this->whenLoaded('children')),
      'option_category_title' => $this->option_category_title,
      'translations' => TranslationsResource::collection($this->whenLoaded('translations'))->relationName('option_category'),
      'abbreviation' => $this->abbreviation,
      'input_type' => $this->input_type,
//      'number_format' => $this->number_format,
      'order' => $this->order
    ];
  }
}

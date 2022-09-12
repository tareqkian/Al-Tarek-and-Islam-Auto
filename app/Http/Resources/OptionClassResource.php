<?php

namespace App\Http\Resources;

use App\Models\OptionSubClass;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionClassResource extends JsonResource
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
      'option_class_title' => $this->option_class_title,
      'translations' => TranslationsResource::collection($this->whenLoaded('translations'))->relationName('option_class'),
      'sub_classes' => OptionSubClassResource::collection($this->whenLoaded('sub_classes')),
      'children' => OptionSubClassResource::collection($this->whenLoaded('children')),
      'order' => $this->order
    ];
  }
}

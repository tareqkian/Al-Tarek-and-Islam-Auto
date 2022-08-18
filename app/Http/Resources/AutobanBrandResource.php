<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class AutobanBrandResource extends JsonResource
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
          "id" => $this->id,
          "brand_image" => URL::to($this->brand_image),
          "brand_title" => $this->brand_title,
          "translations" => TranslationsResource::collection($this->translations)->relationName("autoban_brand"),
          "models" => AutobanModelResource::collection($this->whenLoaded('models')),
        ];
    }
}

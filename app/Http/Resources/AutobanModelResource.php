<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class AutobanModelResource extends JsonResource
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
          'model_image' => URL::to($this->model_image),
          'gallery' => newCarGalleryResource::collection($this->whenLoaded("gallery")),
          'model_title' => $this->model_title,
          "translations" => TranslationsResource::collection($this->translations)->relationName("autoban_model"),
          "brand" => new AutobanBrandResource($this->whenLoaded('brand')),
          "autobans" => AutobanResource::collection($this->whenLoaded('autobans')),
        ];
    }
}

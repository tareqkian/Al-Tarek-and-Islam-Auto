<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AutobanPriceResource extends JsonResource
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
          'official' => $this->official,
          'commercial' => $this->commercial,
          'sale' => $this->sale,
          'translations' => TranslationsResource::collection($this->translations)->relationName('autoban_price')
        ];
    }
}

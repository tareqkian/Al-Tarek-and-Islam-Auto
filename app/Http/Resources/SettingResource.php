<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SettingResource extends JsonResource
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
          "key" => $this->key,
          "display_name" => $this->display_name,
          "value" => (str_contains($this->value,'logos') ? URL::to($this->value) : $this->value),
          "type" => $this->type,
          "group" => $this->group
        ];
    }
}

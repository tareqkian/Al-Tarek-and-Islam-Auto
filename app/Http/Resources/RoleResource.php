<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class RoleResource extends JsonResource
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
      "name" => $this->name,
      "display_name" => $this->display_name,
      "translations" => TranslationsResource::collection($this->translations)->relationName("role"),
      "permissions" => PermissionResource::collection($this->whenLoaded('permissions'))
    ];
  }
}

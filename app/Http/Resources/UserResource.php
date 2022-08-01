<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
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
      'name' => $this->name,
      'email' => $this->email,
      'role' => new RoleResource($this->role),
      'avatar' => URL::to($this->avatar) ?? null,
      'settings' => $this->settings,
      'created_at' => date("d-m-Y",strtotime($this->created_at)),
      'updated_at' => date("d-m-Y",strtotime($this->updated_at))
    ];
  }
}

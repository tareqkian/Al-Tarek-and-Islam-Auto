<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslationsResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */

  protected $relationName;

  public function relationName($value){
    $this->relationName = $value;
    return $this;
  }

  public function toArray($request)
  {
    return collect(parent::toArray($request))->except(["id",$this->relationName."_id"]);
  }


  public static function collection($resource){
    return new TranslationsResourceCollection($resource);
  }

}

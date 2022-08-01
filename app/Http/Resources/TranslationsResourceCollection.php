<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TranslationsResourceCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */

  protected $relationName;

  public function relationName($value){
    $this->relationName = $value;
    return $this;
  }

  public function toArray($request){
    return $this->collection->map(function(TranslationsResource $resource) use($request){
      return $resource->relationName($this->relationName)->toArray($request);
    })->all();
  }
}

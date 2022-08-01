<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */

  // permissionsLayout: `${y.importedComponent.split('/')[y.importedComponent.split('/').length-2].toLowerCase()}_${y.importedComponent.split('/').pop().replace(/.vue/g,'').toLowerCase()}`

  public function toArray($request)
  {
    $arr = (array) explode('/',$this->importedComponent);
    $child = str_replace('.vue','',end($arr));
    unset($arr[count($arr)-1]);
    $parent = end($arr);
    return [
      'id' => $this->id,
      'icon_class' => $this->icon_class,
      'importedComponent' => $this->importedComponent,
      'permissionsLayout' => strtolower($parent.'_'.$child),
      'route' => $this->route,
      'target' => $this->target,
      'title' => $this->title,
      'order' => $this->order,
      'children' => $this->collection($this->children)
    ];
  }
}

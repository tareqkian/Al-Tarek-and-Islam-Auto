<?php

namespace App\Http\Resources;

use App\Models\Autoban;
use App\Models\OptionCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AutobanResource extends JsonResource
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
      'model' => new AutobanModelResource($this->whenLoaded('model')),
      'type' => new AutobanTypeResource($this->whenLoaded('type')),
      'year' => new AutobanYearResource($this->whenLoaded('year')),
      'price' => new AutobanPriceResource($this->whenLoaded('price')),
      'price_list_appearance' => ($this->price_list_appearance || false),
      'market_availability' => ($this->market_availability || false),
      'order' => $this->order,
      'reviewed' => $this->reviewed,
      'pivots' => AutobanCategoryResource::collection($this->whenLoaded('pivots')),
      "pivotsOptionsRequired" => $this->whenLoaded('pivotsOptionsRequired',function (){
        $pivotRequiredId = $this->pivotsOptionsRequired->map(function ($v){return $v->category->id;});
        $categoryRequired = OptionCategory::where('required',1)->whereNotIn('id',$pivotRequiredId)->get();
        return [
          'data' => $categoryRequired,
          'count' => $categoryRequired->count()
        ];
      }),
      "specs_no" => $this->specs_no,
      "specs_req0" => $this->specs_req0,
      "specs_req" => $this->specs_req,
      "latestOptionUpdate" => $this->latestOptionUpdate ? date("d/m/Y",strtotime($this->latestOptionUpdate)) : '',

      'range' => $this->whenLoaded('range',function (){
        $official = $this->price->official;
        $gearBoxId = collect($this->pivots)
          ->reject(function ($v){return $v['category']['id'] !== 28;})
          ->map(function ($v){return $v['options'];})
          ->flatten()
          ->map(function ($v){ return $v['id']; })
          ->first();
        $bodyShapeId = collect($this->pivots)
          ->reject(function ($v){return $v['category']['id'] !== 24;})
          ->map(function ($v){return $v['options'];})
          ->flatten()
          ->map(function ($v){ return $v['id']; })
          ->first();
        $upRange = Autoban::whereHas("price.translations", function ($q) use ($official) {
          $q->where("official", ">", $official)
            ->where("official", "<", ($official * 1.1));
        })
          ->whereHas("pivots.options", function ($q) use ($gearBoxId) {
            $q->where("option_id",$gearBoxId);
          })
          ->whereHas("pivots.options", function ($q) use ($bodyShapeId) {
            $q->where('option_id',$bodyShapeId);
          })
          ->where('autoban_model_id','!=',$this->autoban_model_id)
          ->with('model.brand', 'type', 'year', 'price')->withoutGlobalScope('order')
          ->select('autoban_model_id',DB::raw(
            'MAX(autoban_price_id) autoban_price_id, MAX(autoban_year_id) autoban_year_id, MAX(autoban_type_id) autoban_type_id, MAX(id) id'
          ))
          ->groupBy('autoban_model_id')
          ->take(5)
          ->get()
          ->sortBy('price.official');;

        $downRange = Autoban::whereHas("price.translations", function ($q) use ($official) {
          $q->where("official", "<", $official)
            ->where("official", ">", ($official - ($official * 0.1)));
        })
          ->whereHas("pivots.options", function ($q) use ($gearBoxId) {
            $q->where("option_id",$gearBoxId);
          })
          ->whereHas("pivots.options", function ($q) use ($bodyShapeId) {
            $q->where('option_id',$bodyShapeId);
          })
          ->where('autoban_model_id','!=',$this->autoban_model_id)
          ->with('model.brand', 'type', 'year', 'price')->withoutGlobalScope('order')
          ->select('autoban_model_id',DB::raw(
            'MAX(autoban_price_id) autoban_price_id, MAX(autoban_year_id) autoban_year_id, MAX(autoban_type_id) autoban_type_id, MAX(id) id'
          ))
          ->groupBy('autoban_model_id')
          ->take(5)
          ->get()
          ->sortBy('price.official');

        return [
          'up' => self::collection($upRange),
          'down' => self::collection($downRange),
        ];
      })
    ];
  }
}

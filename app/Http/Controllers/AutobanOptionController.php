<?php

namespace App\Http\Controllers;

use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Http\Requests\StoreAutobanRequest;
use App\Http\Requests\UpdateAutobanRequest;
use App\Models\AutobanCategory;
use App\Models\Option;
use App\Models\OptionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutobanOptionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = Autoban::with('pivotCategories')->get();
    return [
      'data' => $data
    ];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $autoban = Autoban::find($id);
    $data = $autoban->load('pivots.category', 'pivots.options');
    return new AutobanResource($data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanRequest  $request
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request,$id)
  {
    $autoban = Autoban::find($id);
    $data = $request->all();
    $dataC = [];
    foreach ($data as $key => $item) {
      $category = OptionCategory::find($key);
      if ( !is_array($item) && in_array($category->input_type,['text','number']) ) {
        if ($item) $dataC[$key] = ['option'=>$item];
      } else {
        $dataC[] = $key;
      }
    }

    $autoban->autobanCateory()->sync($dataC);

    foreach ($autoban->pivots as $item) {
      $category = OptionCategory::find($item['option_category_id']);
      $syncedOptions = $data[$item['option_category_id']];
      if ( is_array($syncedOptions) )
        $item->options()->sync($syncedOptions);
      else
        if ( !str_contains($syncedOptions,'/') && !in_array($category->input_type,['text','number']) )
          $item->options()->sync($syncedOptions);
    }


    $autoban = Autoban::where("autobans.id",$id)
      ->with([
        'model',
        'type',
        'year',
        'price',
        'pivotsOptionsRequired'
      ])
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')
      ->where('autoban_model_translations.locale',app()->getLocale())
      ->join('autoban_models','autoban_models.id','=','autobans.autoban_model_id')
      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')
      ->where('autoban_brand_translations.locale',app()->getLocale())
      ->join('autoban_type_translations','autoban_type_translations.autoban_type_id','=','autobans.autoban_type_id')
      ->where('autoban_type_translations.locale',app()->getLocale())
      ->join('autoban_year_translations','autoban_year_translations.autoban_year_id','=','autobans.autoban_year_id')
      ->where('autoban_year_translations.locale',app()->getLocale())
      ->leftJoinSub(
        DB::table('autoban_category')
          ->leftJoin("autoban_category_option", 'autoban_category_option.autoban_category_id', '=', 'autoban_category.id')
          ->select('autoban_category.autoban_id', DB::raw("(COUNT(autoban_category_option.autoban_category_id)+SUM(IF(autoban_category.option,1,0))) specs_no"))
          ->groupBy("autoban_category.autoban_id"),
        "autoban_category", function ($join){ $join->on('autobans.id','=','autoban_category.autoban_id'); }
      )
      ->leftJoinSub(
        DB::table('autoban_category','autoban_category2')
          ->leftJoin("option_categories",function ($join){
            $join->on("autoban_category2.option_category_id","=","option_categories.id")
              ->where("option_categories.required",1);
          })
          ->select('autoban_category2.autoban_id', DB::raw("COUNT(option_categories.id) AS specs_req0"))
          ->groupBy("autoban_category2.autoban_id"),
        "autoban_category2", function ($join){ $join->on('autobans.id','=','autoban_category2.autoban_id'); }
      )
      ->leftJoinSub(
        DB::table("option_categories", "ops")
          ->where("required",1)
          ->select("ops.required",DB::raw("COUNT(ops.id) specs_req"))
          ->groupBy("ops.required"),
        "ops",
        function ($join) {
          $join->where("required",1);
        }
      )
      ->leftJoinSub(
        DB::table('autoban_category')
          ->select(DB::raw("MAX(updated_at) AS latestOptionUpdate"),"autoban_id")
          ->groupBy("autoban_id"),
        "autoban_category3", function ($join) {$join->on('autobans.id','=','autoban_category3.autoban_id');}
      )
      ->select(
        'autobans.*',
        'specs_no',
        'latestOptionUpdate',
        DB::raw("(specs_req - IF(specs_req0,specs_req0,0)) specs_req")
      )
      ->first();


    return new AutobanResource($autoban);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function destroy(Autoban $autoban)
  {
    //
  }
}

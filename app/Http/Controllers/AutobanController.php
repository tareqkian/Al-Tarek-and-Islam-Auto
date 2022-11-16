<?php

namespace App\Http\Controllers;

use App\Events\AutobanAdder;
use App\Events\AutobanDeleter;
use App\Events\AutobanEditor;
use App\Http\Resources\AutobanModelResource;
use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Http\Requests\StoreAutobanRequest;
use App\Http\Requests\UpdateAutobanRequest;
use App\Models\AutobanBrand;
use App\Models\AutobanModel;
use App\Models\AutobanModelTranslation;
use App\Models\AutobanPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AutobanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $column = "CONCAT(brand_title, ' ', model_title)";
    if ( $request->input('column') === 'brand_title' )
    {
      $column = $request->input('column');
    }
    if ( $request->input('column') === 'model_title' )
    {
      $column = "CONCAT(brand_title, ' ', model_title)";
    }
    $filter = "%{$request->input('filter')}%";
    if ( $request->input('filterMode') === 'like' || $request->input('filter') === '' )
    {
      $filter = "%{$request->input('filter')}%";
    }
    else
    {
      $filter = "{$request->input('filter')}";
    }

    $autoban = Autoban::with(
      'model',
      'type',
      'year',
      'price',
      'pivotsOptions',
      'latestOptionUpdate',
      'pivotsOptionsRequired'
      )
      ->select('autobans.*')
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')->where('autoban_model_translations.locale',app()->getLocale())
      ->join('autoban_models','autoban_models.id','=','autobans.autoban_model_id')
      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')->where('autoban_brand_translations.locale',app()->getLocale())
      ->join('autoban_year_translations','autoban_year_translations.autoban_year_id','=','autobans.autoban_year_id')->where('autoban_year_translations.locale',app()->getLocale())
      ->whereRaw("$column LIKE ?", [$filter])
      ->orderBy('autoban_brand_translations.brand_title')
      ->orderBy('autoban_model_translations.model_title')
      ->orderBy('autoban_year_translations.year_number')
      ->orderBy('order')
      ->paginate($request->perPage ?: 10);

    return AutobanResource::collection($autoban);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanRequest $request)
  {
    $idsIns = [];
    $validated = $request->validated();
    $types = $validated['autoban_type_id'];
    foreach ($types as $type) {
      $validated['autoban_type_id'] = $type;
      $validated['order'] = Autoban::latestOrder($validated['autoban_model_id'])+1;
      $price = AutobanPrice::create([
        'en' => [
          'official' => 0,
          'commercial' => 0,
          'sale' => 0,
        ],
        'ar' => [
          'official' => 0,
          'commercial' => 0,
          'sale' => 0,
        ]
      ]);
      $validated['autoban_price_id'] = $price->id;
      $autoban = Autoban::create($validated);

      $idsIns[] = $autoban->id;
    }

    $autobans = Autoban::whereIn('id',$idsIns)->with(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations',
    )->get();

    return AutobanResource::collection($autobans);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $model = AutobanModel::find($id);
    $model->load([
      "autobans" => function($query){
        $query->with(
          'type',
          'year',
          'price',
        )->withCount('pivots')->orderBy('order');
      }
    ]);
    return new AutobanModelResource($model);
  }

  public function showAutoban($id)
  {
    $autoban = Autoban::find($id);
    return new AutobanResource($autoban->load('pivots.category', 'pivots.options')->withCount('pivots'));
  }

  public function showByBrand($id)
  {
    $autoban = Autoban::find($id);
    $autoban->load('model');
    $brand = $autoban['model']['autoban_brand_id'];
    $models = AutobanModel::where('autoban_brand_id',$brand)->pluck('id');
    $autoban = Autoban::whereIn('autobans.autoban_model_id',$models)
      ->whereNotIn('autobans.id',[$id])
      ->with( 'model', 'type', 'year', 'price')
      ->select('*','autobans.id as id')
      ->withCount('pivots')
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')->where('autoban_model_translations.locale',app()->getLocale())
      ->join('autoban_models','autoban_models.id','=','autobans.autoban_model_id')
      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')->where('autoban_brand_translations.locale',app()->getLocale())
      ->join('autoban_year_translations','autoban_year_translations.autoban_year_id','=','autobans.autoban_year_id')->where('autoban_year_translations.locale',app()->getLocale())
      ->orderBy('autoban_brand_translations.brand_title')
      ->orderBy('autoban_model_translations.model_title')
      ->orderBy('autoban_year_translations.year_number')
      ->orderBy('order')
      ->get();

    return [
      'data' => $autoban,
      'brand' => $brand,
      'model' => $models,
    ];
    /*return AutobanResource::collection($autoban);*/
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanRequest  $request
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanRequest $request, Autoban $autoban)
  {
    $validated = $request->validated();
    $validated['autoban_type_id'] = $validated['autoban_type_id'][0];
    $autoban->update($validated);
    $autoban->load(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations'
    )->withCount('pivots');
    return new AutobanResource($autoban);
  }

  public function reOrder(Request $request)
  {
    $orderedAutoban = [];
    foreach ($request->all() as $item) {
      $autoban = Autoban::find($item['id']);
      $autoban->order = $item['order'];
      $autoban->save();
      $autoban->load(
        'model.brand.translations',
        'type.translations',
        'year.translations',
        'price.translations',
      )->withCount('pivots');
      $orderedAutoban[] = $autoban;
    }
    return AutobanResource::collection($orderedAutoban);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function destroy(Autoban $autoban)
  {
//    broadcast(new AutobanDeleter($autoban));
    $autoban->delete();
    return [ "status" => 204 ];
  }
}

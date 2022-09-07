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
    $autoban = Autoban::with(
      'model',
      'type',
      'year',
      'price')
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')->where('autoban_model_translations.locale','en')
      ->join('autoban_models','autoban_models.id','=','autobans.autoban_model_id')
      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')->where('autoban_brand_translations.locale','en')
      ->join('autoban_year_translations','autoban_year_translations.autoban_year_id','=','autobans.autoban_year_id')->where('autoban_year_translations.locale','en')

      ->orderBy('autoban_brand_translations.brand_title')
      ->orderBy('autoban_model_translations.model_title')
      ->orderBy('autoban_year_translations.year_number')
      ->orderBy('order')
      ->paginate($request->perPage ?: 10);

    /*->sortBy(['model.brand.brand_title','model.model_title','year.year_number','order'])*/
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
    $validated = $request->validated();
    $validated['order'] = Autoban::latestOrder($validated['autoban_model_id']);

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


    $autoban->load(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations',
    );

    broadcast(new AutobanAdder(new AutobanResource($autoban)));
    return new AutobanResource($autoban);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $model = AutobanModel::findOrFail($id);
    $model->load([
      "autobans" => function($query){
        $query->with('type','year','price')->orderBy('order');
      }
    ]);
    return new AutobanModelResource($model);
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
    $autoban->update($validated);
    $autoban->load(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations'
    );
    broadcast(new AutobanEditor(new AutobanResource($autoban)));
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
      );
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
    broadcast(new AutobanDeleter($autoban));
    $autoban->delete();
    return [ "status" => 204 ];
  }
}

<?php
namespace App\Http\Controllers;

use App\Events\AutobanEditor;
use App\Http\Resources\AutobanBrandResource;
use App\Http\Resources\AutobanModelResource;
use App\Http\Resources\AutobanPriceResource;
use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Models\AutobanBrand;
use App\Models\AutobanModel;
use App\Models\AutobanPrice;
use Illuminate\Http\Request;

class PricelistController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $brands = AutobanBrand::whereHas('models.autobans',function ($query){
      $query->where('price_list_appearance',true);})
      ->get()
      ->sortBy('brand_title');
    return AutobanBrandResource::collection($brands);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $brand = AutobanBrand::find($id);
    $brand->load([
      "models" => function ($query) {
        return $query->whereHas('autobans')
          ->with(['gallery','autobans' => function ($q) {
            return $q->where('price_list_appearance',true)
              ->with(
                'year',
                'price',
              );
          }]);
      }
    ]);
    $brand->models = $brand->models->sortBy('model_title');
    return new AutobanBrandResource($brand);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function showDetails($id)
  {
    $model = AutobanModel::find($id);
    $model->load([
      "gallery",
      "brand",
      'autobans' => function ($q) {
        return $q->where('price_list_appearance',true)
          ->with(
            'model.brand',
            'type',
            'year',
            'price',
            'range',
            'pivots.category',
            'pivots.options'
          );
      }
    ]);
    return new AutobanModelResource($model);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request,$id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}

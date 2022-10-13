<?php
namespace App\Http\Controllers;

use App\Events\AutobanEditor;
use App\Http\Resources\AutobanBrandResource;
use App\Http\Resources\AutobanModelResource;
use App\Http\Resources\AutobanPriceResource;
use App\Http\Resources\AutobanResource;
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
    $brand = AutobanBrand::findOrFail($id);
    $brand->load([
      "models.autobans" => function ($query) {
        return $query->where('price_list_appearance',true)
          ->with('model.brand','type', 'year', 'price');
      }
    ]);
    $brand->models = $brand->models->sortBy(function ($val){
      $val->autobans = $val->autobans->sortBy(['year.year_number','order']);
      return $val;
    });
    $brand->models = $brand->models->sortBy('model_title');
    return new AutobanBrandResource($brand);
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

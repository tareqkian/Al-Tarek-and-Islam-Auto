<?php

namespace App\Http\Controllers;

use App\Events\OptionAdder;
use App\Events\OptionDeleter;
use App\Events\OptionEditor;
use App\Http\Resources\AutobanResource;
use App\Http\Resources\OptionCategoryResource;
use App\Http\Resources\OptionClassResource;
use App\Http\Resources\OptionResource;
use App\Models\Autoban;
use App\Models\Option;
use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\UpdateOptionRequest;
use App\Models\OptionCategory;
use App\Models\OptionClass;
use App\Models\OptionSubClass;

class OptionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $options = OptionClass::with('translations')
      ->get()
      ->sortBy('order');
    return OptionClassResource::collection($options);
  }

  public function optionTree()
  {
    $options = OptionClass::with([
      'sub_classes' => function($x) {
        $x->orderBy('order');
        $x->with([
          'option_categories' => function($x) {
            $x->orderBy('order');
            $x->with([
              'options' => function($x) {
                $x->orderBy('order');
              }
            ]);
          }
        ]);
      }
    ])->orderBy('order')
      ->get();
    return OptionClassResource::collection($options);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreOptionRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreOptionRequest $request)
  {
    if ( $request->input('order') ) {
      foreach ($request->input('order') as $item) {
        $optionCategory = Option::findOrFail($item['id']);
        $optionCategory->update($item);
      }
      return;
    }
    $validated = $request->validated();
    $validated['order'] = Option::order($validated['option_category_id'])->order+1;
    $option = Option::create($validated);
    return new OptionResource($option);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Option  $option
   * @return \Illuminate\Http\Response
   */
  public function show(Option $option)
  {
    //
  }

  public function optionClassCars($id)
  {
    $option = OptionClass::find($id)->sub_classes->load('option_categories.AutobanCategory');
    $option = $option->map(function ($x){
      return $x->option_categories;
    })->flatten()->map(function ($x){
      $x->AutobanCategory = $x->AutobanCategory->map(function ($z){
        return $z->autoban_id;
      });
      return $x->AutobanCategory;
    })->flatten();
    $autoban = Autoban::whereNotIn('id',$option)
      ->with('model.brand','year','price','type')
      ->where("price_list_appearance",1)
      ->get()
      ->sortBy('model.brand.brand_title')
      ->sortBy('model.model_title');
    return AutobanResource::collection($autoban);
  }
  public function optionSubClassCars($id)
  {
    $option = OptionSubClass::find($id)->option_categories->load('AutobanCategory');

    $option = $option->map(function ($x){
      $x->AutobanCategory = $x->AutobanCategory->map(function ($z){
        return $z->autoban_id;
      });
      return $x->AutobanCategory;
    })->flatten();

    $autoban = Autoban::whereNotIn('id',$option)
      ->with('model.brand','year','price','type')
      ->where("price_list_appearance",1)
      ->get()
      ->sortBy('model.brand.brand_title')
      ->sortBy('model.model_title');
    return AutobanResource::collection($autoban);
  }
  public function optionCategoryCars($id)
  {
    $option = OptionCategory::find($id)->AutobanCategory;
    $option = $option->map(function ($x){
      return $x->autoban_id;
    });
    $autoban = Autoban::whereNotIn('id',$option)
      ->with('model.brand','year','price','type')
      ->where("price_list_appearance",1)
      ->get()
      ->sortBy('model.brand.brand_title')
      ->sortBy('model.model_title');
    return AutobanResource::collection($autoban);
  }
  public function optionCars($id)
  {
    $option = Option::find($id)->option_autobans->load('autoban_category');
    $option = $option->map(function ($x){
      return $x->autoban_category->autoban_id;
    });
    $autoban = Autoban::whereNotIn('id',$option)
      ->with('model.brand','year','price','type')
      ->where("price_list_appearance",1)
      ->get()
      ->sortBy('model.brand.brand_title')
      ->sortBy('model.model_title');
    return AutobanResource::collection($autoban);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateOptionRequest  $request
   * @param  \App\Models\Option  $option
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateOptionRequest $request, Option $option)
  {
    $option->update($request->validated());
//    broadcast(new OptionEditor(new OptionResource($option)));
    return new OptionResource($option);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Option  $option
   * @return \Illuminate\Http\Response
   */
  public function destroy(Option $option)
  {
//    broadcast(new OptionDeleter($option));
    $option->delete();
    return ['status'=>204];
  }
}

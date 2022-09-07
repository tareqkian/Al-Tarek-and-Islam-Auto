<?php

namespace App\Http\Controllers;

use App\Http\Resources\OptionCategoryResource;
use App\Http\Resources\OptionClassResource;
use App\Http\Resources\OptionResource;
use App\Models\Option;
use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\UpdateOptionRequest;
use App\Models\OptionCategory;
use App\Models\OptionClass;

class OptionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(\Illuminate\Http\Request $request)
  {
//    $options = Option::with('translations','option_category')
//      ->paginate($request->perPage ?: 10);
//    return OptionResource::collection($options);
    $options = OptionClass::with('children.children.children','translations')
      ->get()
      ->sortBy('order');
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
    $option->delete();
    return ['status'=>204];
  }
}

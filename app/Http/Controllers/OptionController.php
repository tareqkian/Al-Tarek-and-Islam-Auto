<?php

namespace App\Http\Controllers;

use App\Events\OptionAdder;
use App\Events\OptionDeleter;
use App\Events\OptionEditor;
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
    $options = OptionClass::with('translations')
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
    broadcast(new OptionAdder(new OptionResource($option)));
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
    broadcast(new OptionEditor(new OptionResource($option)));
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
    broadcast(new OptionDeleter($option));
    $option->delete();
    return ['status'=>204];
  }
}

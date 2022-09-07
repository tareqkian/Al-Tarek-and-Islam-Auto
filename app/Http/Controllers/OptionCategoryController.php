<?php

namespace App\Http\Controllers;

use App\Events\OptionCategoryAdder;
use App\Events\OptionCategoryDeleter;
use App\Events\OptionCategoryEditor;
use App\Http\Resources\OptionCategoryResource;
use App\Models\OptionCategory;
use App\Http\Requests\StoreOptionCategoryRequest;
use App\Http\Requests\UpdateOptionCategoryRequest;

class OptionCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $optionCategory = OptionCategory::with('translations','option_sub_class')->paginate(10);
    return OptionCategoryResource::collection($optionCategory);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreOptionCategoryRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreOptionCategoryRequest $request)
  {
    $optionCategory = OptionCategory::create($request->validated());
    $optionCategory->load('translations','option_sub_class');
    broadcast(new OptionCategoryAdder(new OptionCategoryResource($optionCategory)));
    return new OptionCategoryResource($optionCategory);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\OptionCategory  $optionCategory
   * @return \Illuminate\Http\Response
   */
  public function show(OptionCategory $optionCategory)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateOptionCategoryRequest  $request
   * @param  \App\Models\OptionCategory  $optionCategory
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateOptionCategoryRequest $request, OptionCategory $optionCategory)
  {
    $optionCategory->update($request->validated());
    $optionCategory->load('translations','option_sub_class');
    broadcast(new OptionCategoryEditor(new OptionCategoryResource($optionCategory)));
    return new OptionCategoryResource($optionCategory);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\OptionCategory  $optionCategory
   * @return \Illuminate\Http\Response
   */
  public function destroy(OptionCategory $optionCategory)
  {
    broadcast(new OptionCategoryDeleter($optionCategory));
    $optionCategory->delete();
    return ['status'=>204];
  }
}

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
    $optionCategory = OptionCategory::with('translations','option_sub_class')->get()->sortBy('order');
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
    if ( $request->input('order') ) {
      foreach ($request->input('order') as $item) {
        $optionCategory = OptionCategory::findOrFail($item['id']);
        $optionCategory->update($item);
      }
      return;
    }
    $validated = $request->validated();
    $validated['order'] = (OptionCategory::order($request->input('option_sub_class_id'))->order + 1);
    $optionCategory = OptionCategory::create($validated);
    $optionCategory->load(['options' => function($x){$x->orderBy('order');}]);
//    broadcast(new OptionCategoryAdder(new OptionCategoryResource($optionCategory)));
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
    $optionCategory->load([
      'options' => function($x) {
        $x->orderBy('order');
      }
    ]);
    return new OptionCategoryResource($optionCategory);
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
    $optionCategory->load(['options' => function($x){$x->orderBy('order');}]);
//    broadcast(new OptionCategoryEditor(new OptionCategoryResource($optionCategory)));
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
//    broadcast(new OptionCategoryDeleter($optionCategory));
    $optionCategory->delete();
    return ['status'=>204];
  }
}

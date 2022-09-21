<?php

namespace App\Http\Controllers;

use App\Events\OptionSubClassesAdder;
use App\Events\OptionSubClassesDeleter;
use App\Events\OptionSubClassesEditor;
use App\Http\Resources\OptionSubClassResource;
use App\Models\OptionSubClass;
use App\Http\Requests\StoreOptionSubClassRequest;
use App\Http\Requests\UpdateOptionSubClassRequest;

class OptionSubClassController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $optionSubClasses = OptionSubClass::with('option_class')
      ->get()
      ->sortBy('option_class.order');
    return OptionSubClassResource::collection($optionSubClasses);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreOptionSubClassRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreOptionSubClassRequest $request)
  {
    if ( $request->input('order') ) {
      foreach ($request->input('order') as $item) {
        $optionSubClass = OptionSubClass::findOrFail($item['id']);
        $optionSubClass->update($item);
      }
      return;
    }
    $validated = $request->validated();
    $validated['order'] = (OptionSubClass::order($request->input('option_class_id'))->order + 1);
    $optionSubClass = OptionSubClass::create($validated);
    $optionSubClass->load(['option_categories' => function($x) {$x->orderBy('order');}]);
    broadcast(new OptionSubClassesAdder(new OptionSubClassResource($optionSubClass)));
    return new OptionSubClassResource($optionSubClass);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\OptionSubClass  $optionSubClass
   * @return \Illuminate\Http\Response
   */
  public function show(OptionSubClass $optionSubClass)
  {
    $optionSubClass->load(['option_categories' => function($x) {$x->orderBy('order');}]);
    return new OptionSubClassResource($optionSubClass);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateOptionSubClassRequest  $request
   * @param  \App\Models\OptionSubClass  $optionSubClass
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateOptionSubClassRequest $request, OptionSubClass $optionSubClass)
  {
    $optionSubClass->update($request->validated());
    $optionSubClass->load(['option_categories' => function($x) {$x->orderBy('order');}]);
    broadcast(new OptionSubClassesEditor(new OptionSubClassResource($optionSubClass)));
    return new OptionSubClassResource($optionSubClass);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\OptionSubClass  $optionSubClass
   * @return \Illuminate\Http\Response
   */
  public function destroy(OptionSubClass $optionSubClass)
  {
    broadcast(new OptionSubClassesDeleter($optionSubClass));
    $optionSubClass->delete();
    return ['status'=>204];
  }
}

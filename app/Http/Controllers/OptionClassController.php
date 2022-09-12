<?php

namespace App\Http\Controllers;

use App\Events\OptionClassesAdder;
use App\Events\OptionClassesDeleter;
use App\Events\OptionClassesEditor;
use App\Events\OptionEditor;
use App\Http\Resources\OptionClassResource;
use App\Models\OptionClass;
use App\Http\Requests\StoreOptionClassRequest;
use App\Http\Requests\UpdateOptionClassRequest;

class OptionClassController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $optionClasses = OptionClass::with('translations')
      ->orderBy('order')
      ->get();
    return OptionClassResource::collection($optionClasses);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreOptionClassRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreOptionClassRequest $request)
  {
    $optionClass = OptionClass::create($request->validated());
    $optionClass->load('children.children.children');
    broadcast(new OptionClassesAdder(new OptionClassResource($optionClass)));
    return new OptionClassResource($optionClass);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\OptionClass  $optionClass
   * @return \Illuminate\Http\Response
   */
  public function show(OptionClass $optionClass)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateOptionClassRequest  $request
   * @param  \App\Models\OptionClass  $optionClass
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateOptionClassRequest $request, OptionClass $optionClass)
  {
    $optionClass->update($request->safe()->only(['en.option_class_title','ar.option_class_title']));
    $optionClass->load('children.children.children');
    broadcast(new OptionClassesEditor(new OptionClassResource($optionClass)));
    return new OptionClassResource($optionClass);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\OptionClass  $optionClass
   * @return \Illuminate\Http\Response
   */
  public function destroy(OptionClass $optionClass)
  {
    broadcast(new OptionClassesDeleter($optionClass));
    $optionClass->delete();
    return ['status'=>204];
  }
}

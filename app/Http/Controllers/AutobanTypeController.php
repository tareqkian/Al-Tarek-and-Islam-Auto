<?php

namespace App\Http\Controllers;

use App\Events\ModelDeleter;
use App\Events\TypeAdder;
use App\Events\TypeDeleter;
use App\Events\TypeEditor;
use App\Http\Resources\AutobanTypeResource;
use App\Models\AutobanType;
use App\Http\Requests\StoreAutobanTypeRequest;
use App\Http\Requests\UpdateAutobanTypeRequest;

class AutobanTypeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $types = AutobanType::with('translations')->paginate(10);
    return AutobanTypeResource::collection($types);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanTypeRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanTypeRequest $request)
  {
    $type = AutobanType::create($request->validated());
    broadcast(new TypeAdder(new AutobanTypeResource($type)));
    return new AutobanTypeResource($type);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AutobanType  $autobanType
   * @return \Illuminate\Http\Response
   */
  public function show(AutobanType $autobanType)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanTypeRequest  $request
   * @param  \App\Models\AutobanType  $autobanType
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanTypeRequest $request, AutobanType $autobanType)
  {
    $validated = $request->all();
    unset($validated['id']);
    $autobanType->update($validated);
    broadcast(new TypeEditor(new AutobanTypeResource($autobanType)));
    return new AutobanTypeResource($autobanType);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\AutobanType  $autobanType
   * @return \Illuminate\Http\Response
   */
  public function destroy(AutobanType $autobanType)
  {
    broadcast(new TypeDeleter($autobanType));
    $autobanType->delete();
    return [ "status" => 204 ];
  }
}

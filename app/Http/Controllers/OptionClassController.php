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
use Illuminate\Http\Request;

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
    if ( $request->input('order') ) {
      foreach ($request->input('order') as $item) {
        $optionClass = OptionClass::findOrFail($item['id']);
        $optionClass->update($item);
      }
      return;
    }
    $validated = $request->validated();
    $validated['order'] = (OptionClass::order()->order + 1);
    $optionClass = OptionClass::create($validated);

    $optionClass->load(['sub_classes' => function($x){$x->orderBy('order');}]);
//    broadcast(new OptionClassesAdder(new OptionClassResource($optionClass)));
    return new OptionClassResource($optionClass);
  }
  public function showWithChildrens($id)
  {
    $optionClass = OptionClass::findOrFail($id);
    $optionClass->load([
      'sub_classes' => function($x){
        $x->orderBy('order');
        $x->with(['option_categories' => function($x){
          $x->orderBy('order');
          $x->with(['options' => function($x){
            $x->orderBy('order');
          }]);
        }]);
      },
    ]);
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
    $optionClass->load(['sub_classes' => function($x){$x->orderBy('order');}]);
    return new OptionClassResource($optionClass);
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
    $optionClass->update($request->validated());
    $optionClass->load(['sub_classes' => function($x){$x->orderBy('order');}]);
//    broadcast(new OptionClassesEditor(new OptionClassResource($optionClass)));
    return new OptionClassResource($optionClass);
  }

  public function reorder(Request $request)
  {
    return $request->all();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\OptionClass  $optionClass
   * @return \Illuminate\Http\Response
   */
  public function destroy(OptionClass $optionClass)
  {
//    broadcast(new OptionClassesDeleter($optionClass));
    $optionClass->delete();
    return ['status'=>204];
  }
}

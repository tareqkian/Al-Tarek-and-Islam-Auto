<?php

namespace App\Http\Controllers;

use App\Events\TaskAdder;
use App\Events\TaskDeleter;
use App\Events\TaskEditor;
use App\Http\Resources\AutobanPriceTaskResource;
use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Models\AutobanPriceTask;
use App\Http\Requests\StoreAutobanPriceTaskRequest;
use App\Http\Requests\UpdateAutobanPriceTaskRequest;

class AutobanPriceTaskController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $tasks = AutobanPriceTask::with('brand')
      ->get()
      ->sortBy(function ($q){
        return $q->updated_at->addDays($q->duration);
      });
    return AutobanPriceTaskResource::collection($tasks);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanPriceTaskRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanPriceTaskRequest $request)
  {
    $validated = $request->validated();

    $tasks = [];
    foreach ($validated['autoban_brand_id'] as $item) {
      $task = AutobanPriceTask::create([
        'autoban_brand_id' => $item['id'],
        'duration' => $validated['duration']
      ]);
      $tasks[] = $task->load('brand');
    }
    broadcast(new TaskAdder(AutobanPriceTaskResource::collection($tasks)));
    return AutobanPriceTaskResource::collection($tasks);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AutobanPriceTask  $autobanPriceTask
   * @return \Illuminate\Http\Response
   */
  public function show(AutobanPriceTask $autobanPriceTask)
  {
//    $autoban = Autoban::with('model', 'type', 'year', 'price')
//      ->whereRelation('model.brand','id',$autobanPriceTask->autoban_brand_id)
//      ->get()
//      ->sortBy(['model.brand.brand_title','model.model_title','year.year_number','order']);
    $autoban = Autoban::with('model', 'type', 'year', 'price')
      ->whereRelation('model.brand','id',$autobanPriceTask->autoban_brand_id)
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')->where('autoban_model_translations.locale','en')
      ->orderBy('autoban_model_translations.model_title')
      ->paginate(10);

    return AutobanResource::collection($autoban);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanPriceTaskRequest  $request
   * @param  \App\Models\AutobanPriceTask  $autobanPriceTask
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanPriceTaskRequest $request, AutobanPriceTask $autobanPriceTask)
  {
    if ( !$request->input('touch') ){
      $autobanPriceTask->update($request->safe()->only(['duration']));
    } else {
      $autobanPriceTask->touch();
    }
    broadcast(new TaskEditor(new AutobanPriceTaskResource($autobanPriceTask->load('brand'))));
    return new AutobanPriceTaskResource($autobanPriceTask);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\AutobanPriceTask  $autobanPriceTask
   * @return \Illuminate\Http\Response
   */
  public function destroy(AutobanPriceTask $autobanPriceTask)
  {
    broadcast(new TaskDeleter($autobanPriceTask->load('brand')));
    $autobanPriceTask->delete();
    return ['status'=>204];
  }
}

<?php

namespace App\Observers;

use App\Http\Resources\UserResource;
use App\Models\AutobanBrand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BrandObserver
{
  public $afterCommit = true;

  /**
   * Handle the AutobanBrand "created" event.
   *
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return void
   */
  public function created(Model $model)
  {
    Log::channel('Types')
      ->info('',[
        'type' => "create",
        'user' => auth()->user(),
        'table' => $model->getTable(),
        'originalModel' => $model->getOriginal(),
      ]);
  }

  /**
   * Handle the AutobanBrand "updated" event.
   *
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return void
   */
  public function updated(Model $model)
  {
    $changes = $model->getChanges();
    unset($changes['updated_at']); unset($changes['created_at']);

    foreach ($changes as $key=>$change) {
      $original = $model->getOriginal($key);
      Log::channel("Brands-Models")
        ->info('',[
          'type' => "update",
          'user' => auth()->user(),
          'table' => $model->getTable(),
          'originalModel' => $model->getOriginal(),
          'original' => $original,
          'changes' => $change,
        ]);
    }
  }

  /**
   * Handle the AutobanBrand "deleted" event.
   *
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return void
   */
  public function deleted(Model $model)
  {
    Log::channel('Types')
      ->info('',[
        'type' => "delete",
        'user' => auth()->user(),
        'table' => $model->getTable(),
        'originalModel' => $model->getOriginal(),
      ]);
  }
}

<?php

namespace App\Observers;

use App\Models\AutobanYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class YearObserver
{
  public $afterCommit = true;

  /**
   * Handle the AutobanYear "created" event.
   *
   * @param  \App\Models\AutobanYear  $autobanType
   * @return void
   */
  public function created(Model $model)
  {
    if ( !str_contains($model->getTable(),'translations') ) {
      Log::channel('Years')
        ->info('',[
          'type' => "create",
          'user' => auth()->user(),
          'table' => $model->getTable(),
          'originalModel' => $model,
        ]);
    }
  }

  /**
   * Handle the AutobanYear "updated" event.
   *
   * @param  \App\Models\AutobanYear  $autobanType
   * @return void
   */
  public function updated(Model $model)
  {
    $changes = $model->getChanges();
    unset($changes['updated_at']); unset($changes['created_at']);

    foreach ($changes as $key=>$change) {
      $original = $model->getOriginal($key);
      Log::channel("Years")
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
   * Handle the AutobanYear "deleted" event.
   *
   * @param  \App\Models\AutobanYear  $autobanType
   * @return void
   */
  public function deleted(Model $model)
  {
    Log::channel('Years')
      ->info('',[
        'type' => "delete",
        'user' => auth()->user(),
        'table' => $model->getTable(),
        'originalModel' => $model,
      ]);
  }
}

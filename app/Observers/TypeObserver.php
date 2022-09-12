<?php

namespace App\Observers;

use App\Http\Resources\AutobanTypeResource;
use App\Models\AutobanType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TypeObserver
{
  public $afterCommit = true;

  /**
   * Handle the AutobanType "created" event.
   *
   * @param  \App\Models\AutobanType  $autobanType
   * @return void
   */
  public function created(Model $model)
  {
    Log::channel('Types')
      ->info('',[
        'type' => "created",
        'user' => auth()->user(),
        'table' => $model->getTable(),
        'originalModel' => $model->getOriginal(),
      ]);
  }

  /**
   * Handle the AutobanType "updated" event.
   *
   * @param  \App\Models\AutobanType  $autobanType
   * @return void
   */
  public function updated(Model $model)
  {
    $changes = $model->getChanges();
    unset($changes['updated_at']); unset($changes['created_at']);

    foreach ($changes as $key=>$change) {
      $original = $model->getOriginal($key);
      Log::channel("Types")
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
   * Handle the AutobanType "deleted" event.
   *
   * @param  \App\Models\AutobanType  $autobanType
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

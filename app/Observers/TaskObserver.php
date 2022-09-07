<?php

namespace App\Observers;

use App\Models\AutobanPriceTask;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
  /**
   * Handle the AutobanPriceTask "created" event.
   *
   * @param  \App\Models\AutobanPriceTask  $autobanPriceTask
   * @return void
   */
  public function created(AutobanPriceTask $autobanPriceTask)
  {
    Log::channel('Tasks')
      ->info('',[
        'type' => "create",
        'user' => auth()->user(),
        'table' => $autobanPriceTask->getTable(),
        'originalModel' => $autobanPriceTask->load('brand'),
      ]);
  }

  /**
   * Handle the AutobanPriceTask "updated" event.
   *
   * @param  \App\Models\AutobanPriceTask  $autobanPriceTask
   * @return void
   */
  public function updated(AutobanPriceTask $autobanPriceTask)
  {
    $changes = $autobanPriceTask->getChanges();
    unset($changes['updated_at']); unset($changes['created_at']);

    foreach ($changes as $key=>$change) {
      $original = $autobanPriceTask->getOriginal($key);
      Log::channel("Tasks")
        ->info('',[
          'type' => "update",
          'user' => auth()->user(),
          'table' => $autobanPriceTask->getTable(),
          'originalModel' => $autobanPriceTask->load('brand')->getOriginal(),
          'original' => $original,
          'changes' => $change,
        ]);
    }
  }

  /**
   * Handle the AutobanPriceTask "deleted" event.
   *
   * @param  \App\Models\AutobanPriceTask  $autobanPriceTask
   * @return void
   */
  public function deleted(AutobanPriceTask $autobanPriceTask)
  {
    Log::channel('Tasks')
      ->info('',[
        'type' => "delete",
        'user' => auth()->user(),
        'table' => $autobanPriceTask->getTable(),
        'originalModel' => $autobanPriceTask->load('brand'),
      ]);
  }
}

<?php

namespace App\Observers;

use App\Models\Autoban;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AutobanObserver
{
  public $afterCommit = true;

  /**
   * Handle the Autoban "created" event.
   *
   * @param  \App\Models\Autoban  $autobanBrand
   * @return void
   */
  public function created(Model $model)
  {
    Log::channel('Autobans')
      ->info('',[
        'type' => "create",
        'user' => auth()->user(),
        'table' => $model->getTable(),
        'originalModel' => $model->load('model.brand.translations','type.translations','year.translations'),
      ]);
  }

  /**
   * Handle the Autoban "updated" event.
   *
   * @param  \App\Models\Autoban  $autobanBrand
   * @return void
   */
  public function updated(Model $model)
  {
    $changes = $model->getChanges();
    unset($changes['updated_at']); unset($changes['created_at']);

    foreach ($changes as $key=>$change) {
      $original = $model->getOriginal($key);
      Log::channel("Autobans")
        ->info('',[
          'type' => "update",
          'user' => auth()->user(),
          'table' => $model->getTable(),
          'originalModel' => $model->load('model.brand.translations','type.translations','year.translations'),
          'original' => $original,
          'changes' => $change,
        ]);
    }
  }

  /**
   * Handle the Autoban "deleted" event.
   *
   * @param  \App\Models\Autoban  $autobanBrand
   * @return void
   */
  public function deleted(Model $model)
  {
    Log::channel('Autobans')
      ->info('',[
        'type' => "delete",
        'user' => auth()->user(),
        'table' => $model->getTable(),
        'originalModel' => $model->getOriginal(),
      ]);
  }
}

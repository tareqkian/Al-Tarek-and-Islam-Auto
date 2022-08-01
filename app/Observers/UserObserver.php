<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
  /**
   * Handle the User "created" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function created(User $user)
  {
    Log::channel("custom")->info(
      auth()->user()." created $user in Users"
    );
  }

  /**
   * Handle the User "updated" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function updated(User $user)
  {
    $changes = $user->getChanges();
    unset($changes['updated_at']);
    foreach ($changes as $key=>$item) {
      $original = $user->getOriginal($key);
      if ( $key === 'password' ) {
        Log::channel("custom")->info(
          auth()->user()->name." Changed his Password"
        );
      } else {
        if ( auth()->user() ) {
          Log::channel("custom")->info(
            auth()->user()->name." changed $key from $original to $item"
          );
        } else {
          Log::channel("custom")->info(
            "$user->name created his First Password and Logged In"
          );
        }
      }
    }
  }

  /**
   * Handle the User "deleted" event.
   *
   * @param  \App\Models\User  $user
   * @return void
   */
  public function deleted(User $user)
  {
    //
  }
}

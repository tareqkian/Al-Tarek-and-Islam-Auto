<?php

namespace App\Providers;

use App\Models\AutobanBrand;
use App\Models\AutobanBrandTranslation;
use App\Models\AutobanModel;
use App\Models\AutobanModelTranslation;
use App\Models\AutobanType;
use App\Models\AutobanTypeTranslation;
use App\Models\AutobanYear;
use App\Models\AutobanYearTranslation;
use App\Models\User;
use App\Observers\BrandObserver;
use App\Observers\TypeObserver;
use App\Observers\UserObserver;
use App\Observers\YearObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();
    // User Observer
    User::observe(UserObserver::class);

    // Brands-Models Observer
    $autobanBrand = [
      AutobanBrand::class,
      AutobanBrandTranslation::class,
      AutobanModel::class,
      AutobanModelTranslation::class
    ];
    foreach ($autobanBrand as $model) $model::observe(BrandObserver::class);

    // Type Observer
    $autobanType = [
      AutobanType::class,
      AutobanTypeTranslation::class,
    ];
    foreach ($autobanType as $model) $model::observe(TypeObserver::class);

    // Year Observer
    $autobanYear = [
      AutobanYear::class,
      AutobanYearTranslation::class,
    ];
    foreach ($autobanYear as $model) $model::observe(YearObserver::class);

  }
}

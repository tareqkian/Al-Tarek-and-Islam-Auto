<?php

namespace App\Http\Controllers;

use App\Events\AutobanEditor;
use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Models\AutobanPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutobanPriceController extends Controller
{
  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function priceUpdate(Request $request,$id)
  {
    $validated = $request->validate([
      'official' => 'required',
      'commercial' => 'required',
      'sale' => 'required',
    ]);
    $validated = [
      'en' => [
        'official' => $validated['official'],
        'commercial' => $validated['commercial'],
        'sale' => $validated['sale'],
      ],
      'ar' => [
        'official' => $validated['official'],
        'commercial' => $validated['commercial'],
        'sale' => $validated['sale'],
      ]
    ];
    $autobanPrice = AutobanPrice::findOrFail($id);

    $autobanPrice->update($validated);

    $autoban = Autoban::where('autoban_price_id',$autobanPrice->id)->with(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations',
    )->get()->first();

//    broadcast(new AutobanEditor(new AutobanResource($autoban)));

    Log::channel("Prices")
      ->info('',[
        'type' => "update",
        'user' => auth()->user(),
        'table' => $autobanPrice->getTable(),
        'originalModel' => $autobanPrice->load(['autoban'=>function($q){
          $q->with('model.brand.translations', 'type.translations', 'year.translations');
        }]),
      ]);

    return new AutobanResource($autoban);
  }

}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use Illuminate\Http\Request;

class AutobanComparisonController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  array  $ids
   * @return \Illuminate\Http\Response
   */
  public function show($ids)
  {
    $autobans = Autoban::whereIn('id',explode(',',$ids))
      ->with(
        'model.brand',
        'type',
        'year',
        'pivots.category',
        'pivots.options'
      )
      ->get();
    return AutobanResource::collection($autobans);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}

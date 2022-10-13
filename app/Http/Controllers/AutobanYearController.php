<?php

namespace App\Http\Controllers;

//use Alkoumi\LaravelArabicNumbers\Numbers;
use App\Events\YearAdder;
use App\Events\YearDeleter;
use App\Events\YearEditor;
use App\Http\Resources\AutobanYearResource;
use App\Models\AutobanYear;
use App\Http\Requests\StoreAutobanYearRequest;
use App\Http\Requests\UpdateAutobanYearRequest;

class AutobanYearController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $years = AutobanYear::with('translations')->paginate(10);
    return AutobanYearResource::collection($years);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanYearRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanYearRequest $request)
  {
    $validated = $request->all();
    $validated['ar']['year_number'] = $validated['en']['year_number'];
    unset($validated['id']);
    $year = AutobanYear::create($validated);
//    broadcast(new YearAdder(new AutobanYearResource($year)));
    return new AutobanYearResource($year);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AutobanYear  $autobanYear
   * @return \Illuminate\Http\Response
   */
  public function show(AutobanYear $autobanYear)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanYearRequest  $request
   * @param  \App\Models\AutobanYear  $autobanYear
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanYearRequest $request, AutobanYear $autobanYear)
  {
    $validated = $request->all();
    $validated['ar']['year_number'] = $validated['en']['year_number'];
    unset($validated['id']);
    $autobanYear->update($validated);
//    broadcast(new YearEditor(new AutobanYearResource($autobanYear)));
    return new AutobanYearResource($autobanYear);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\AutobanYear  $autobanYear
   * @return \Illuminate\Http\Response
   */
  public function destroy(AutobanYear $autobanYear)
  {
//    broadcast(new YearDeleter($autobanYear));
    $autobanYear->delete();
    return [ "status" => 204 ];
  }
}

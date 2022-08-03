<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TranslatorController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    /*return [
      "data" => config('translatable')
    ];*/
    $tables = DB::select('SHOW TABLES LIKE "%_translations"');
    return [
      "data" => array_map('current',$tables)
    ];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    /*$locales = glob("trans/**");
    foreach ($locales as $item) {
        $path = public_path($item."/".$request->input('parent')."/");
        if ( !File::exists($path) ) {
            File::makeDirectory($path, 0777, true);
        }
        $filePath = public_path($item."/".$request->input('parent')."/".$request->input('route').".json");
        File::put($filePath,"");
    }
    return ["status" => 200];*/
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $relation = explode("_",$id)[0];
    $translation = DB::select("SELECT * FROM $id ");

    $translation = array_reduce(
      $translation,
      function ($a,$b) use ($relation) {
        $b = (array) $b;
        $rel = $b[$relation.'_id'];
        unset($b[$relation.'_id']);
        $a[$rel][] = $b;
        return $a;
      },
      []
    );
    $locals = array_reduce(
      array_keys(config('translatable.locales')),
      function ($a,$b) use ($translation){
        foreach ($translation as $trans) {
          $a[$b][] = array_values(
            array_filter(
              $trans,
              function ($x) use ($b) {
                if ( $x["locale"] === $b ) return $x;
                return false;
              }
            )
          );
        }
        return $a;
      }
      ,[]
    );

    return [
      "data" => $locals
    ];
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
    return [
      "data" => $request->all()
    ];
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

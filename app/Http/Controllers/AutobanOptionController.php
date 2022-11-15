<?php

namespace App\Http\Controllers;

use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Http\Requests\StoreAutobanRequest;
use App\Http\Requests\UpdateAutobanRequest;
use App\Models\AutobanCategory;
use App\Models\Option;
use App\Models\OptionCategory;
use Illuminate\Http\Request;

class AutobanOptionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = Autoban::with('pivotCategories')->get();
    return [
      'data' => $data
    ];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $autoban = Autoban::find($id);
    $data = $autoban->load('pivots.category', 'pivots.options');
    return new AutobanResource($data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanRequest  $request
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
    public function update(Request $request,$id)
    {
        $autoban = Autoban::find($id);
        $data = $request->all();
        $dataC = [];
        foreach ($data as $key => $item) {
            $category = OptionCategory::find($key);
            if ( !is_array($item) && in_array($category->input_type,['text','number']) ) {
                if ($item) $dataC[$key] = ['option'=>$item];
            } else {
                $dataC[] = $key;
            }
        }

        $autoban->autobanCateory()->sync($dataC);

        foreach ($autoban->pivots as $item) {
            $category = OptionCategory::find($item['option_category_id']);
            $syncedOptions = $data[$item['option_category_id']];
            if ( is_array($syncedOptions) )
                $item->options()->sync($syncedOptions);
            else
                if ( !str_contains($syncedOptions,'/') && !in_array($category->input_type,['text','number']) )
                    $item->options()->sync($syncedOptions);
        }

        return [
            'autoban' => $autoban,
            'autobanPivot' => $autoban->pivots,
            'autobanCateory' => $autoban->autobanCateory,
            'data' => $data,
            'dataKeys' => array_keys($data),
            'dataC' => $dataC,
        ];
    }

    /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function destroy(Autoban $autoban)
  {
    //
  }
}

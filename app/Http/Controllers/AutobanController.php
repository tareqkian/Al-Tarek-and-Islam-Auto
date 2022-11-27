<?php

namespace App\Http\Controllers;

use App\Events\AutobanAdder;
use App\Events\AutobanDeleter;
use App\Events\AutobanEditor;
use App\Http\Resources\AutobanModelResource;
use App\Http\Resources\AutobanResource;
use App\Models\Autoban;
use App\Http\Requests\StoreAutobanRequest;
use App\Http\Requests\UpdateAutobanRequest;
use App\Models\AutobanBrand;
use App\Models\AutobanModel;
use App\Models\AutobanModelTranslation;
use App\Models\AutobanPrice;
use App\Models\OptionCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AutobanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $column = "CONCAT(brand_title, ' ', model_title)";
    if ( $request->input('column') === 'brand_title' )
    {
      $column = $request->input('column');
    }
    if ( $request->input('column') === 'model_title' )
    {
      $column = "CONCAT(brand_title, ' ', model_title)";
    }
    $filter = "%{$request->input('filter')}%";
    if ( $request->input('filterMode') === 'like' || $request->input('filter') === '' )
    {
      $filter = "%{$request->input('filter')}%";
    }
    else
    {
      $filter = "{$request->input('filter')}";
    }

    $sortColumn = $request->input('sortColumn');
    $sortDir = $request->input('sortDir');

    $sortQuery = "";
    if ( !$sortColumn || $sortColumn === 'model' )
    {
      $sortQuery = "
        brand_title $sortDir,
        model_title $sortDir,
        year_number,
        autobans.order,
        autobans.id
      ";
    }
    else
    {
      $sortQuery = "$sortColumn $sortDir";
    }


    $categoryRequired = OptionCategory::where('required',1)->get();

    $autoban = Autoban::with([
      'model',
      'type',
      'year',
      'price',
      'pivotsOptionsRequired'
    ])
    ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')
      ->where('autoban_model_translations.locale',app()->getLocale())
    ->join('autoban_models','autoban_models.id','=','autobans.autoban_model_id')
    ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')
      ->where('autoban_brand_translations.locale',app()->getLocale())
    ->join('autoban_type_translations','autoban_type_translations.autoban_type_id','=','autobans.autoban_type_id')
      ->where('autoban_type_translations.locale',app()->getLocale())
    ->join('autoban_year_translations','autoban_year_translations.autoban_year_id','=','autobans.autoban_year_id')
      ->where('autoban_year_translations.locale',app()->getLocale())

    ->join(
      'autoban_price_translations',
      'autoban_price_translations.autoban_price_id',
      '=',
      'autobans.autoban_price_id'
    )->where('autoban_price_translations.locale',app()->getLocale())

    ->leftJoinSub(
      DB::table('autoban_category')
        ->leftJoin("autoban_category_option", 'autoban_category_option.autoban_category_id', '=', 'autoban_category.id')
        ->select('autoban_category.autoban_id', DB::raw("(COUNT(autoban_category_option.autoban_category_id)+SUM(IF(autoban_category.option,1,0))) specs_no"))
        ->groupBy("autoban_category.autoban_id"),
      "autoban_category", function ($join){ $join->on('autobans.id','=','autoban_category.autoban_id'); }
    )
    ->leftJoinSub(
      DB::table('autoban_category','autoban_category2')
        ->leftJoin("option_categories",function ($join){
          $join->on("autoban_category2.option_category_id","=","option_categories.id")
            ->where("option_categories.required",1);
        })
        ->select('autoban_category2.autoban_id', DB::raw("COUNT(option_categories.id) AS specs_req0"))
        ->groupBy("autoban_category2.autoban_id"),
      "autoban_category2", function ($join){ $join->on('autobans.id','=','autoban_category2.autoban_id'); }
    )
    ->leftJoinSub(
      DB::table("option_categories", "ops")
        ->where("required",1)
        ->select("ops.required",DB::raw("COUNT(ops.id) specs_req"))
        ->groupBy("ops.required"),
      "ops",
      function ($join) {
        $join->where("required",1);
      }
    )

    ->leftJoinSub(
      DB::table('autoban_category')
        ->select(DB::raw("MAX(updated_at) AS latestOptionUpdate"),"autoban_id")
        ->groupBy("autoban_id"),
      "autoban_category3", function ($join) {$join->on('autobans.id','=','autoban_category3.autoban_id');}
    )

    ->select(
      'autobans.*',
      'specs_no',
      'latestOptionUpdate',
      DB::raw("(specs_req - IF(specs_req0,specs_req0,0)) specs_req")
    )
    ->whereRaw("$column LIKE ?", [$filter])
    ->orderByRaw($sortQuery)
    ->paginate($request->perPage ?: 10);



    /*return $autoban;*/
    return AutobanResource::collection($autoban);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanRequest $request)
  {
    $idsIns = [];
    $validated = $request->validated();
    $types = $validated['autoban_type_id'];
    foreach ($types as $type) {
      $validated['autoban_type_id'] = $type;
      $validated['order'] = Autoban::latestOrder($validated['autoban_model_id'])+1;
      $price = AutobanPrice::create([
        'en' => [
          'official' => 0,
          'commercial' => 0,
          'sale' => 0,
        ],
        'ar' => [
          'official' => 0,
          'commercial' => 0,
          'sale' => 0,
        ]
      ]);
      $validated['autoban_price_id'] = $price->id;
      $autoban = Autoban::create($validated);

      $idsIns[] = $autoban->id;
    }

    $autobans = Autoban::whereIn('id',$idsIns)->with(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations',
    )->get();

    return AutobanResource::collection($autobans);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $model = AutobanModel::find($id);
    $model->load([
      "autobans" => function($query){
        $query->with(
          'type',
          'year',
          'price',
        )->withCount('pivots')->orderBy('order');
      }
    ]);
    return new AutobanModelResource($model);
  }

  public function showAutoban($id)
  {
    $autoban = Autoban::find($id);
    return new AutobanResource($autoban->load('pivots.category', 'pivots.options')->withCount('pivots'));
  }

  public function showByBrand($id)
  {
    $autoban = Autoban::find($id);
    $autoban->load('model');
    $brand = $autoban['model']['autoban_brand_id'];
    $models = AutobanModel::where('autoban_brand_id',$brand)->pluck('id');
    $autoban = Autoban::whereIn('autobans.autoban_model_id',$models)
      ->whereNotIn('autobans.id',[$id])
      ->with( 'model', 'type', 'year', 'price')
      ->select('*','autobans.id as id')
      ->withCount('pivots')
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autobans.autoban_model_id')->where('autoban_model_translations.locale',app()->getLocale())
      ->join('autoban_models','autoban_models.id','=','autobans.autoban_model_id')
      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')->where('autoban_brand_translations.locale',app()->getLocale())
      ->join('autoban_year_translations','autoban_year_translations.autoban_year_id','=','autobans.autoban_year_id')->where('autoban_year_translations.locale',app()->getLocale())
      ->orderBy('autoban_brand_translations.brand_title')
      ->orderBy('autoban_model_translations.model_title')
      ->orderBy('autoban_year_translations.year_number')
      ->orderBy('order')
      ->get();

    return [
      'data' => $autoban,
      'brand' => $brand,
      'model' => $models,
    ];
    /*return AutobanResource::collection($autoban);*/
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanRequest  $request
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanRequest $request, Autoban $autoban)
  {
    $validated = $request->validated();
    $validated['autoban_type_id'] = $validated['autoban_type_id'][0];
    $autoban->update($validated);
    $autoban->load(
      'model.brand.translations',
      'type.translations',
      'year.translations',
      'price.translations'
    )->withCount('pivots');
    return new AutobanResource($autoban);
  }

  public function autobanReview(Request $request, $id)
  {
    $autoban = Autoban::find($id);
    $data = ["reviewed" => ($request->input('pivotsOptionsRequired.v') ? $request->input("pivotsOptionsRequired.count") : 0)];
    $autoban->update($data);
    return new AutobanResource($autoban);
  }

  public function reOrder(Request $request)
  {
    $orderedAutoban = [];
    foreach ($request->all() as $item) {
      $autoban = Autoban::find($item['id']);
      $autoban->order = $item['order'];
      $autoban->save();
      $autoban->load(
        'model.brand.translations',
        'type.translations',
        'year.translations',
        'price.translations',
      )->withCount('pivots');
      $orderedAutoban[] = $autoban;
    }
    return AutobanResource::collection($orderedAutoban);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Autoban  $autoban
   * @return \Illuminate\Http\Response
   */
  public function destroy(Autoban $autoban)
  {
//    broadcast(new AutobanDeleter($autoban));
    $autoban->delete();
    return [ "status" => 204 ];
  }

/*  public function images(Request $request)
  {
    $folder = public_path("newCarGallery");
    $filesInFolder = File::directories($folder);
    $fileNames = [];
    foreach($filesInFolder as $path) {
      $file = pathinfo($path);
      $fileNames[] = $file ;
    }
    $autobanModel = AutobanModel::with("brand")
      ->get()
      ->map(function ($x){
        return [
          'name' => Str::lower("{$x->brand->brand_title}_{$x->model_title}"),
          'id' => $x->id
        ];
      })->reject(function ($x) use ($fileNames) {
        $nameArr = collect($fileNames)->map(function ($x){ return $x['filename']; })->toArray();
        return !in_array($x['name'],$nameArr);
      })->values();

    $newFiles = [];
    foreach ($autobanModel as $files) {
      $oldFolder = public_path("newCarGallery/{$files['name']}");
      $renameFolder = public_path("newCarGallery/{$files['id']}");
      File::move($oldFolder,$renameFolder);
      $autobanModel = AutobanModel::find($files['id']);
      $filesPath = File::files($renameFolder);
      $newFiles[$files['id']] = collect($filesPath)->map(function ($x){
        $x = pathinfo($x);
        $path = "{$x['dirname']}/{$x['basename']}";
        $whatIWant = substr($path, strpos($path, "public\\") + 7);
        return ["image" => $whatIWant];
      });
      $autobanModel->gallery()->createMany($newFiles[$files['id']]);
    }
    return [
      "fileNames" => $fileNames,
      "autobanModel" => $autobanModel,
      'newFiles' => $newFiles
    ];
  }
*/
}

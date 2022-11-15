<?php

namespace App\Http\Controllers;

use App\Events\BrandDeleter;
use App\Events\ModelAdder;
use App\Events\ModelDeleter;
use App\Events\ModelEditor;
use App\Http\Resources\AutobanModelResource;
use App\Models\AutobanBrand;
use App\Models\AutobanModel;
use App\Http\Requests\StoreAutobanModelRequest;
use App\Http\Requests\UpdateAutobanModelRequest;
use App\Models\NewCarGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use function React\Promise\all;

class AutobanModelController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $models = AutobanModel::with("translations","brand.translations","gallery")
      ->select("autoban_models.*")
      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autoban_models.id')->where('autoban_model_translations.locale', app()->getLocale())
      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_models.autoban_brand_id')->where('autoban_brand_translations.locale', app()->getLocale())
      ->whereRaw(
        ($request->input('column') === 'brand_title'
          ? $request->input('column')
          : "CONCAT(brand_title, ' ', model_title)")." LIKE ?",
        [ $request->input('filterMode') === 'like' || $request->input('filter') === ''
          ? "%{$request->input('filter')}%"
          : "{$request->input('filter')}"
        ]
      )
      ->orderBy('autoban_brand_translations.brand_title')
      ->orderBy('autoban_model_translations.model_title')
      ->paginate($request->perPage ?: 10);

    return AutobanModelResource::collection($models);
  }
  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return \Illuminate\Http\Response
   */
  public function indexSingler($id)
  {
    $autobanBrand = AutobanBrand::find($id);
    return AutobanModelResource::collection(
      $autobanBrand->models->load('translations','gallery')->sortBy('order')
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanModelRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanModelRequest $request)
  {
    $autobanBrand = AutobanBrand::find($request->input('autoban_brand_id'));
    $data = $request->input('data');
    foreach ($request->input('data') as $index => $item) {
      $dpPath = $this->saveImage($data[$index]['model_image']);
      $data[$index]['model_image'] = $dpPath;
    }
    $models = $autobanBrand->models()->createMany($data);
    return AutobanModelResource::collection($models->load('brand','gallery'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Models\AutobanModel  $autobanModel
   * @return \Illuminate\Http\Response
   */
  public function Gallery(Request $request, $id)
  {
    $autobanModel = AutobanModel::find($id);
    foreach ($request->all() as $image) {
      $imagePath = $this->saveImage($image,"newCarGallery/{$autobanModel->id}/");
      $autobanModel->gallery()->create(['image' => $imagePath]);
    }
    return new AutobanModelResource($autobanModel->load('brand','gallery'));
  }
  public function DeleteGallery($id)
  {
    $image = NewCarGallery::find($id);
    $modelId = $image->autoban_model_id;
    if ( $image->image ) {
      $deletePath = public_path($image->image);
      File::delete($deletePath);
    }
    $image->delete();
    return ["status" => 204, "model_id" => $modelId];
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AutobanModel  $autobanModel
   * @return \Illuminate\Http\Response
   */
  public function show(AutobanModel $autobanModel)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanModelRequest  $request
   * @param  \App\Models\AutobanModel  $autobanModel
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanModelRequest $request, AutobanModel $autobanModel)
  {
    $validated = $request->validated();
    $validated = array_merge($validated,$request->validated()['data'][0]);
    unset($validated['data']);

    if ( isset($validated['model_image']) ) {
      if (preg_match('/^data:image\/(\w+);base64,/',$validated['model_image'],$type)) {
        $dpPath = $this->saveImage($validated['model_image']);
        $validated['model_image'] = $dpPath;
        if ( $autobanModel->model_image ) {
          $deletePath = public_path($autobanModel->model_image);
          File::delete($deletePath);
        }
      } else {
        unset($validated['model_image']);
      }
    } else {
      unset($validated['model_image']);
    }

    $autobanModel->update($validated);
    return new AutobanModelResource($autobanModel->load('brand','gallery'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\AutobanModel  $autobanModel
   * @return \Illuminate\Http\Response
   */
  public function destroy(AutobanModel $autobanModel)
  {
    if ( $autobanModel->model_image ) {
      $deletePath = public_path($autobanModel->model_image);
      File::delete($deletePath);
    }
    $autobanModel->delete();
    return [ "status" => 204 ];
  }

  private function saveImage($image,$dir = "models/")
  {
    if (preg_match('/^data:image\/(\w+);base64,/',$image,$type)) {
      $image = substr($image, strpos($image,',')+1);
      $type = strtolower($type[1]);
      if ( !in_array($type, ['jpg','jpeg','gif','png','webp']) ){
        throw new \Exception("Invalid Type");
      }
      $image = str_replace(' ','+',$image);
      $image = base64_decode($image);
      if ( $image === false ) {
        throw new \Exception("base64_decode Error");
      }
    } else {
      throw new \Exception("did not match data URI with image data");
    }
    /*$dir = $dir;*/
    $file = Str::random().time().'.'.$type;
    $absolutePath = public_path($dir);
    $relativePath = $dir.$file;
    if ( !File::exists($absolutePath) ) {
      File::makeDirectory($absolutePath,0777,true);
    }
    file_put_contents($relativePath,$image);
    return $relativePath;
  }

}

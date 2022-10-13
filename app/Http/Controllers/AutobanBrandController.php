<?php

namespace App\Http\Controllers;

use App\Events\BrandAdder;
use App\Events\BrandDeleter;
use App\Events\BrandEditor;
use App\Events\UsersDeleter;
use App\Http\Resources\AutobanBrandResource;
use App\Models\AutobanBrand;
use App\Http\Requests\StoreAutobanBrandRequest;
use App\Http\Requests\UpdateAutobanBrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AutobanBrandController extends Controller
{
  public function __construct(Request $request)
  {
    App::setLocale($request->header('locale'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
//    $brands = AutobanBrand::with("translations","models.translations")
//      ->join('autoban_brand_translations','autoban_brand_translations.autoban_brand_id','=','autoban_brands.id')->where('autoban_brand_translations.locale','en')
//      ->join('autoban_models','autoban_models.autoban_brand_id','=','autoban_brands.id')
//      ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autoban_models.id')->where('autoban_model_translations.locale','en')
//
//      ->orderBy('autoban_brand_translations.brand_title')
//      ->orderBy('autoban_model_translations.model_title')
//      ->paginate(1);

    $brands = AutobanBrand::with("translations","models.translations")
      ->get()
      ->sortBy(['brand_title']);
    return AutobanBrandResource::collection($brands);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreAutobanBrandRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreAutobanBrandRequest $request)
  {
    $validated = $request->all();
    $dpPath = $this->saveImage($validated['brand_image']);
    $validated['brand_image'] = $dpPath;
    $brand = AutobanBrand::create($validated);
//    broadcast(new BrandAdder(new AutobanBrandResource($brand)));
    return new AutobanBrandResource($brand);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return \Illuminate\Http\Response
   */
  public function show(AutobanBrand $autobanBrand)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateAutobanBrandRequest  $request
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateAutobanBrandRequest $request, AutobanBrand $autobanBrand)
  {
    $validated = $request->all();
    if ( isset($validated['brand_image']) ) {
      if (preg_match('/^data:image\/(\w+);base64,/',$validated['brand_image'],$type)) {
        $dpPath = $this->saveImage($validated['brand_image']);
        $validated['brand_image'] = $dpPath;
        if ( $autobanBrand->brand_image ) {
          $deletePath = public_path($autobanBrand->brand_image);
          File::delete($deletePath);
        }
      } else {
        unset($validated['brand_image']);
      }
    } else {
      unset($validated['brand_image']);
    }
    $autobanBrand->update($validated);
//    broadcast(new BrandEditor(new AutobanBrandResource($autobanBrand)));
    return new AutobanBrandResource($autobanBrand);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\AutobanBrand  $autobanBrand
   * @return \Illuminate\Http\Response
   */
  public function destroy(AutobanBrand $autobanBrand)
  {
    if ( $autobanBrand->brand_image ) {
      $deletePath = public_path($autobanBrand->brand_image);
      File::delete($deletePath);
      foreach ($autobanBrand->models as $model) {
        $deletePath = public_path($model->model_image);
        File::delete($deletePath);
      }
    }
//    broadcast(new BrandDeleter($autobanBrand));
    $autobanBrand->delete();
    return [ "status" => 204 ];
  }


  private function saveImage($image)
  {
    if (preg_match('/^data:image\/(\w+);base64,/',$image,$type)) {
      $image = substr($image, strpos($image,',')+1);
      $type = strtolower($type[1]);
      if ( !in_array($type, ['jpg','jpeg','gif','png']) ){
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
    $dir = "brands/";
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

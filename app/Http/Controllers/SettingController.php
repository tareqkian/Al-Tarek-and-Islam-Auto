<?php

namespace App\Http\Controllers;

use App\Events\SettingsEditor;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SettingController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $settings = Setting::all();
    return SettingResource::collection($settings);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreSettingRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreSettingRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Setting  $setting
   * @return \Illuminate\Http\Response
   */
  public function show(Setting $setting)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateSettingRequest  $request
   * @param  \App\Models\Setting  $setting
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateSettingRequest $request,$id)
  {
    foreach ($request->all() as $key => $item) {
      $dpPath = '';
      if ( str_contains($item,'data:image') ) {
        $oldSettings = Setting::findOrFail($key);
        if ( $oldSettings->value ) {
          $deletePath = public_path($oldSettings->value);
          File::delete($deletePath);
        }

        $dpPath = $this->saveImage($item);
      }
      if (str_contains($item,'logos')) {
        $oldSettings = Setting::findOrFail($key);
        $dpPath = $oldSettings->value;
      }
      $setting = Setting::findOrFail($key);
      $setting->timestamps = false;
      $setting->value = $dpPath ?: $item;
      $setting->save();
    }
    $settings = Setting::all();
//    broadcast(new SettingsEditor(SettingResource::collection($settings)));
    return SettingResource::collection($settings);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Setting  $setting
   * @return \Illuminate\Http\Response
   */
  public function destroy(Setting $setting)
  {
    //
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
    $dir = "logos/";
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

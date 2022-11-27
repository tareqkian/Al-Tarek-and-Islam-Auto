<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SaveMyFile extends Model
{
  public static function image($image,$dir,$watermark="")
  {
    if (preg_match('/^data:image\/(\w+);base64,/',$image,$type)) {
      $image = substr($image, strpos($image,',')+1);
      $type = strtolower($type[1]);
      if ( !in_array($type, ['jpg','jpeg','gif','png','webp','bmp']) ){
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
    $file = Str::random().time().'.webp';
    $absolutePath = public_path($dir);
    $relativePath = $dir.$file;
    if ( !File::exists($absolutePath) ) {
      File::makeDirectory($absolutePath,0777,true);
    }
    /*file_put_contents($relativePath,$image);*/

    $img = Image::make($image);
    if ( $watermark ) {
      $waterMark = public_path($watermark);
      $img->insert($waterMark,"center");
    }
    $img->encode('webp', 90);
    $img->save($relativePath);

    return $relativePath;
  }
}

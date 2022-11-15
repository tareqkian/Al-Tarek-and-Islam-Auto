<?php

namespace App\Http\Requests;

use App\Models\AutobanModel;
use Illuminate\Foundation\Http\FormRequest;

class StoreAutobanModelRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'autoban_brand_id' => 'required|int|exists:autoban_brands,id',
      'data.*.model_image' => 'required|string',
//      'data.*.en.model_title' => 'required|string|regex:/^[a-zA-Z0-9\!-_ ]+$/u|unique:autoban_model_translations,model_title',
      'data.*.en.model_title' => [
        'required',
        'string',
        'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
        /*'|unique:autoban_model_translations,model_title',*/
        /*function ($attribute, $value, $fail) {
          $option = AutobanModel::where('autoban_models.autoban_brand_id',$this->autoban_brand_id)
            ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autoban_models.id')
            ->where('autoban_model_translations.locale','en')
            ->where('autoban_model_translations.model_title',$value)
            ->get();
          if (count($option)) {
            $fail("The :attribute title has already been taken.");
          }
        },*/
      ],
//      'data.*.ar.model_title' => 'required|string|regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/|unique:autoban_model_translations,model_title'
      'data.*.ar.model_title' => [
        'required',
        'string',
        'regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/',
        /*function ($attribute, $value, $fail) {
          $option = AutobanModel::where('autoban_models.autoban_brand_id',$this->autoban_brand_id)
            ->join('autoban_model_translations','autoban_model_translations.autoban_model_id','=','autoban_models.id')
            ->where('autoban_model_translations.locale','ar')
            ->where('autoban_model_translations.model_title',$value)
            ->get();
          if (count($option)) {
            $fail("The :attribute title has already been taken.");
          }
        },*/
      ]
    ];
  }
}

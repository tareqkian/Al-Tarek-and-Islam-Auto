<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutobanRequest extends FormRequest
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
      "autoban_model_id" => "required|int|exists:autoban_models,id",
        "autoban_type_id" => "required|Array",
        "autoban_type_id.*" => [
            "required", "int",
            "exists:autoban_types,id",
//            Rule::unique('autobans','autoban_type_id')
//                ->where('autoban_model_id',$this->autoban_model_id)
//                ->where('autoban_year_id',$this->autoban_year_id)
        ],
      "autoban_year_id" => "required|int|exists:autoban_years,id",
    ];
  }
}

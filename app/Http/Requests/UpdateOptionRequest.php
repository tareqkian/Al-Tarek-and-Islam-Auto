<?php

namespace App\Http\Requests;

use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOptionRequest extends FormRequest
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
      'option_category_id' => 'required|int|exists:option_categories,id',
      'en.option_title' => [
        'required',
        'string',
          'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
        /*Rule::unique('option_translations','option_title')
          ->ignore($this->id,'option_id')*/
          function ($attribute, $value, $fail) {
              $option = Option::where('options.option_category_id',$this->option_category_id)
                  ->join('option_translations','option_translations.option_id','=','options.id')
                  ->where('option_translations.locale','en')
                  ->where('option_translations.option_title',$this['en.option_title'])
                  ->where('option_translations.id',$this->id)
                  ->get();
              if (count($option)) {
                  $fail("The :attribute title has already been taken.");
              }
          },
      ],
      'ar.option_title' => [
        'required',
        'string',
          'regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/',
        /*Rule::unique('option_translations','option_title')
          ->ignore($this->id,'option_id')*/
          function ($attribute, $value, $fail) {
              $option = Option::where('options.option_category_id',$this->option_category_id)
                  ->join('option_translations','option_translations.option_id','=','options.id')
                  ->where('option_translations.locale','ar')
                  ->where('option_translations.option_title',$this['ar.option_title'])
                  ->where('option_translations.id',$this->id)
                  ->get();
              if (count($option)) {
                  $fail("The :attribute title has already been taken.");
              }
          },
      ],
      /*'abbreviation' => 'nullable|string|unique:options,abbreviation,'.$this->id,*/
      'abbreviation' => [
          'nullable',
          'string',
          /*'|unique:options,abbreviation'*/
          function ($attribute, $value, $fail) {
              $option = Option::where('options.option_category_id',$this->option_category_id)
                  ->where('options.abbreviation',$value)
                  ->where('options.id','!=',$this->id)
                  ->get();
              if (count($option)) {
                  $fail("The :attribute title has already been taken.");
              }
          },
      ],
    ];
  }
}

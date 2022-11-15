<?php

namespace App\Http\Requests;

use App\Models\Option;
use App\Models\OptionTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOptionRequest extends FormRequest
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
      'option_category_id' => 'required_without:order|int|exists:option_categories,id',
//      'en.option_title' => 'required_without:order|string|regex:/^[a-zA-Z0-9\!-_ ]+$/u|unique:option_translations,option_title',
      'en.option_title' => [
          'required_without:order',
          'string',
          'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
          function ($attribute, $value, $fail) {
              $option = Option::where('options.option_category_id',$this->option_category_id)
                  ->join('option_translations','option_translations.option_id','=','options.id')
                  ->where('option_translations.locale','en')
                  ->where('option_translations.option_title',$this['en.option_title'])
                  ->get();
              if (count($option)) {
                  $fail("The :attribute title has already been taken.");
              }
          },
      ],
//      'ar.option_title' => 'required_without:order|string|regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/|unique:option_translations,option_title',
      'ar.option_title' => [
          'required_without:order',
          'string',
          'regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/',
          function ($attribute, $value, $fail) {
              $option = Option::where('options.option_category_id',$this->option_category_id)
                  ->join('option_translations','option_translations.option_id','=','options.id')
                  ->where('option_translations.locale','ar')
                  ->where('option_translations.option_title',$this['ar.option_title'])
                  ->get();
              if (count($option)) {
                  $fail("The :attribute title has already been taken.");
              }
          },
      ],
      /*'abbreviation' => 'nullable|string|unique:options',*/
        'abbreviation' => [
            'nullable',
            'string',
            /*'|unique:options,abbreviation'*/
            function ($attribute, $value, $fail) {
                $option = Option::where('options.option_category_id',$this->option_category_id)
                    ->where('options.abbreviation',$this->abbreviation)
                    ->get();
                if (count($option)) {
                    $fail("The :attribute title has already been taken.");
                }
            },
        ],
    ];
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
      $rules = ["name" => "required|regex:(/)|unique:menus,name,{$this->id}"];
      $customMessages = ['regex' => ':attribute should start with a `/`.'];
      $this->validate($rules, $customMessages);
      return $rules;
    }
}

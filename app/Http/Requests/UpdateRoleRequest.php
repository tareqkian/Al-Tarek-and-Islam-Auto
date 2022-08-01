<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
      'name' => 'required|unique:roles,name,'.$this->id,
      /*'display_name' => 'required|unique:role_translations,display_name,'.$this->id*/
      'display_name' => [
        'required',
        Rule::unique('role_translations')->ignore($this->id, 'role_id')
      ]
    ];
  }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuItemRequest extends FormRequest
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
      "menu_id" => "int|exists:menus,id",
      "en.title" => "required|string",
      "ar.title" => "required|string",
      "route" => [
        "nullable",
        Rule::unique('menu_items')
          ->where(fn ($q) => $q->where('menu_id', $this->menu_id))
      ],
      "selectedComponent" => "required_with:route",
      "icon_class" => "nullable"
    ];
  }
}
// Rule::unique('users')->where(fn ($query) => $query->where('account_id', 1))

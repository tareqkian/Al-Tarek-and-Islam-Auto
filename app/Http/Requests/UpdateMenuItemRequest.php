<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuItemRequest extends FormRequest
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
            /* "title" => "required|unique:menu_items,title,".$this->id, */
            "title" => [
                "required",
                Rule::unique('menu_items')
                    ->where(fn ($q) => $q->where('menu_id', $this->menu_id))
                    ->ignore($this->id)
            ],
            "route" => [
                "nullable",
                Rule::unique('menu_items')
                    ->where(fn ($q) => $q->where('menu_id', $this->menu_id))
                    ->ignore($this->id)
            ],
            "selectedComponent" => "required_with:route",
            "icon_class" => "nullable"
        ];
    }
}

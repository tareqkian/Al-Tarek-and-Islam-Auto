<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOptionSubClassRequest extends FormRequest
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
            'id' => 'required_without:order|exists:option_sub_classes,id',
            'option_class_id' => 'required_without:order|int|exists:option_classes,id',
            'en.option_sub_class_title' => [
                'required_without:order',
                'string',
                'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
                Rule::unique(
                    'option_sub_class_translations',
                    'option_sub_class_title'
                )->ignore($this->id,'option_sub_class_id')
            ],
            'ar.option_sub_class_title' => [
                'required_without:order',
                'string',
                'regex:/^[كگچپژیلفقهمو ء-ي 0-9]+$/',
                Rule::unique(
                    'option_sub_class_translations',
                    'option_sub_class_title'
                )->ignore($this->id,'option_sub_class_id')
            ],
        ];
    }
}

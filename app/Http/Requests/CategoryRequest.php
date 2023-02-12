<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = ($this->category?->id ? ',' . $this->category->id . ',id' : '');

        return [
            'name' => 'required|min:2|max:255|unique:categories,name' . $id,
            'description' => 'nullable|min:2|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'name' => mb_strtoupper($this->name)
        ]);
    }
}

<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
        return [
            'name' => 'required|string|min:1|max:255',
            'birthday' => 'date:Y-m-d',
            'gender' => 'required|boolean',
            'sampling_date' => 'required|date_format:Y-m-d H:i',
            'sample_receipt_date' => 'required|date_format:Y-m-d H:i',
            'anamnes' => 'nullable|string',
            'doctor' => 'required',
            'categories' => 'required|array',
            'categories.*.code' => 'required|string|min:2|max:5',
            'categories.*.description' => 'required|string|min:2|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'required|mimes:jpg,jpeg,png|max:200000'
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'gender' => $this->gender == 1,
            'birthday' => $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : null,
            'sampling_date' => $this->sampling_date ? Carbon::parse($this->sampling_date)->format('Y-m-d H:i') : null,
            'sample_receipt_date' => $this->sample_receipt_date ? Carbon::parse($this->sample_receipt_date)->format('Y-m-d H:i') : null,
        ]);
    }
}

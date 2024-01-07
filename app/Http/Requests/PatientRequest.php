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
            'medical_card_number' => 'nullable|string|min:1|max:255',
            'place_of_residence' => 'nullable|string|min:1|max:255',
            'phone' => 'nullable|string|min:1|max:255',
            'birthday' => 'required|date:Y-m-d',
            'gender' => 'required|boolean',
            'created_by' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'created_by' => auth()->id(),
            'gender' => $this->boolean('gender'),
            'birthday' => $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : null,
        ]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientReportRequest extends FormRequest
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
            'morbi' => 'nullable|min:5|max:60000',
            'vitae' => 'nullable|min:5|max:60000',
            'lab_workup' => 'nullable|min:5|max:60000',

            'diagnosis' => 'nullable|min:5|max:60000',
            'mkb' => 'nullable|min:5|max:60000',
            'treatment' => 'nullable|min:5|max:60000',

            'comment' => 'nullable|min:5|max:60000',
            'note' => 'nullable|string',
        ];
    }
}

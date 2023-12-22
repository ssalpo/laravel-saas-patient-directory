<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'case_numbers' => implode(', ', $this->case_numbers),
            'birthday' => $this->birthday->format('d.m.Y'),
            'age' => now()->diff($this->birthday)->format('%y'),
            'gender' => $this->gender,
            'sampling_date' => $this->sampling_date->format('d.m.Y H:i'),
            'sample_receipt_date' => $this->sample_receipt_date->format('d.m.Y H:i'),
            'anamnes' => $this->anamnes,
            'doctor' => $this->doctor->name,
            'medical_clinic' => MedicalClinicResource::make($this->whenLoaded('medicalClinic')),
            'categories' => implode(', ', $this->categories_formatted),
            'microscopic_description' => $this->microscopic_description,
            'diagnosis' => $this->diagnosis,
            'note' => $this->note,
            'comment' => $this->comment,
            'status' => $this->status,
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            'hashid' => $this->hashid,
            'place_of_residence' => $this->place_of_residence,
        ];
    }
}

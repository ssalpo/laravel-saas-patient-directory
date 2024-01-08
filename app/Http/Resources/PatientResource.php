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
            'share_to_user_id' => $this->share_to_user_id,
            'created_by' => $this->created_by,
            'name' => $this->name,
            'place_of_residence' => $this->place_of_residence,
            'medical_card_number' => $this->medical_card_number,
            'phone' => $this->phone,
            'birthday' => $this->birthday->format('d.m.Y'),
            'age' => now()->diff($this->birthday)->format('%y'),
            'gender' => $this->gender,
            'note' => $this->note,
            'note_text_count' => strlen(strip_tags($patient->note ?? '')),
            'comment' => $this->comment,
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            'created_at' => $this->created_at->format('d.m.Y'),
            'current_user_consultations_count' => $this->whenCounted('currentUserConsultations'),
            'currentUserConsultations' => PatientConsultationResource::collection($this->whenLoaded('currentUserConsultations')),
            'consultations' => PatientConsultationResource::collection($this->whenLoaded('consultations')),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('d.m.Y'),
            'patients_count' => $this->whenCounted('patients'),
            'patients' => PatientResource::collection($this->whenLoaded('patients')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'speciality' => SpecialityResource::make($this->whenLoaded('speciality')),
        ];
    }
}

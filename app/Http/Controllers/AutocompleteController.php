<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\MedicalClinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AutocompleteController extends Controller
{
    public function roles(): Collection
    {
        return $this->transformCollection(
            items: Role::where(fn ($q) => $this->searchFinder($q, 'readable_name'))->get(),
            textField: 'readable_name'
        );
    }

    public function permissions(): Collection
    {
        return $this->transformCollection(
            items: Permission::where(fn ($q) => $this->searchFinder($q, 'readable_name'))->get(),
            textField: 'readable_name'
        );
    }

    public function doctors(): Collection
    {
        return $this->transformCollection(
            Doctor::where(fn ($q) => $this->searchFinder($q))->orderByDesc('created_at')->get()
        );
    }

    public function medicalClinics(): Collection
    {
        return $this->transformCollection(
            MedicalClinic::where(fn ($q) => $this->searchFinder($q))->get()
        );
    }

    private function searchFinder(Builder $q, string $field = 'name'): void
    {
        $q->when(request('q'), static fn ($q, $v) => $q->where($field, 'like', '%'.$v.'%'));
    }

    private function transformCollection(Collection $items, string $textField = 'name', string $idField = 'id'): Collection
    {
        return $items->transform(fn ($m) => [
            'id' => $m->{$idField},
            'text' => $m->{$textField},
        ]);
    }
}

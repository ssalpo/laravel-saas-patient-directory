<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            textField: 'readable_name',
            idField: 'name',
        );
    }

    public function permissions(): Collection
    {
        return $this->transformCollection(
            items: Permission::where(fn ($q) => $this->searchFinder($q, 'readable_name'))->get(),
            textField: 'readable_name'
        );
    }

    public function users(): Collection
    {
        return $this->transformCollection(
            items: User::where(fn ($q) => $this->searchFinder($q))->get()
        );
    }

    private function searchFinder(Builder $q, array|string $field = ['id', 'name']): void
    {
        $q->when(request('q'), function ($q, $v) use ($field) {
            foreach ((array) $field as $index => $f) {
                $q->where(
                    fn ($q) => $q->{$index > 0 ? 'orWhere' : 'where'}($f, 'like', '%'.$v.'%')
                );
            }
        });

        $q->when($this->getExceptValues(), fn ($q, $v) => $q->whereNotIn('id', $v));
    }

    private function transformCollection(Collection $items, string $textField = 'name', string $idField = 'id'): Collection
    {
        return $items->transform(fn ($m) => [
            'id' => $m->{$idField},
            'text' => $m->{$textField},
        ]);
    }

    public function getExceptValues(): ?array
    {
        if ($except = request('except')) {
            return explode(',', $except);
        }

        return null;
    }
}

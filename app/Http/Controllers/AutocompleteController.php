<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AutocompleteController extends Controller
{
    public function roles(): array
    {
        return Role::query()
            ->when(request('q'), static fn ($q, $v) => $q->where('readable_name', 'like', '%'.$v.'%'))
            ->get()
            ->transform(fn ($m) => [
                'id' => $m->name,
                'text' => $m->readable_name,
            ])
            ->toArray();
    }

    public function permissions(): array
    {
        return Permission::query()
            ->when(request('q'), static fn ($q, $v) => $q->where('readable_name', 'like', '%'.$v.'%'))
            ->get()
            ->transform(fn ($m) => [
                'id' => $m->id,
                'text' => $m->readable_name,
            ])
            ->toArray();
    }

    private function transformCollection($items)
    {
        return $items->transform(fn ($m) => [
            'id' => $m->id,
            'text' => $m->name,
        ]);
    }
}

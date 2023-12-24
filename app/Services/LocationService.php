<?php

namespace App\Services;

use App\Models\Location;

class LocationService
{
    /**
     * Добавляет новую локацию
     */
    public function store(array $data)
    {
        return Location::create($data);
    }

    /**
     * Обновляет данные локации
     */
    public function update(int $id, array $data)
    {
        $location = Location::findOrFail($id);

        $location->update($data);

        return $location;
    }

    /**
     * Удаляет локацию
     */
    public function destroy(int $id)
    {
        return Location::findOrFail($id)->delete();
    }
}

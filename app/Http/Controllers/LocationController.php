<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class LocationController extends Controller
{
    public function __construct(
        protected readonly LocationService $locationService
    ) {
    }

    /**
     * Возвращает список локаций
     */
    public function index(): Response
    {
        $locations = LocationResource::collection(
            Location::paginate(100)
        );

        return inertia('Locations/Index', compact('locations'));
    }

    /**
     * Возвращает форму добавления локации
     */
    public function create(): Response
    {
        return inertia('Locations/Edit');
    }

    /**
     * Добавляет новую локацию
     */
    public function store(LocationRequest $request): JsonResponse|RedirectResponse
    {
        $location = $this->locationService->store($request->validated());

        if ($request->has('modal')) {
            return response()->json(LocationResource::make($location));
        }

        return redirect()->route('locations.index');
    }

    /**
     * Возвращает форму редактирования локации
     */
    public function edit(Location $location): Response
    {
        return inertia('Locations/Edit', compact('location'));
    }

    /**
     * Обновляет данные локации
     */
    public function update(LocationRequest $request, int $id): RedirectResponse
    {
        $this->locationService->update($id, $request->validated());

        return redirect()->route('locations.index');
    }

    /**
     * Удаляет локацию
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->locationService->destroy($id);

        return redirect()->route('locations.index');
    }
}

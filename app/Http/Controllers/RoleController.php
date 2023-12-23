<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        protected readonly RoleService $roleService
    ) {
        $this->middleware('can:read_roles')->only('index');
        $this->middleware('can:create_roles')->only(['create', 'store']);
        $this->middleware('can:edit_roles')->only(['edit', 'update']);
    }

    /**
     * Возвращает список всех ролей
     */
    public function index(): Response
    {
        $roles = Role::orderByDesc('created_at')->paginate(100);

        return inertia('Roles/Index', [
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * Возвращает форму для создания роли
     */
    public function create(): Response
    {
        return inertia('Roles/Edit');
    }

    /**
     * Создает новую роль
     */
    public function store(RoleRequest $request): JsonResponse|RedirectResponse
    {
        $role = $this->roleService->store($request->validated());

        if ($request->has('modal')) {
            return response()->json(RoleResource::make($role));
        }

        return redirect()->route('roles.index');
    }

    /**
     * Возвращает форму для редактирования роли
     */
    public function edit(Role $role): Response
    {
        $role->load('permissions');

        return inertia('Roles/Edit', [
            'role' => RoleResource::make($role),
        ]);
    }

    /**
     * Обновляет данные роли
     */
    public function update(RoleRequest $request, int $id): RedirectResponse
    {
        $this->roleService->update($id, $request->validated());

        return redirect()->route('roles.index');
    }
}

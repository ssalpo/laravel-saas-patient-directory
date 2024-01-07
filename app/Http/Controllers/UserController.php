<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        protected readonly UserService $userService
    ) {
        $this->middleware('can:manage_users');
    }

    /**
     * Показывает список пользователей
     */
    public function index(): Response
    {
        $users = UserResource::collection(
            User::query()
                ->with('roles')
                ->orderByDESC('created_at')
                ->paginate(100)
        );

        return inertia('Users/Index', compact('users'));
    }

    /**
     * Показывает форму создания пользователя
     */
    public function create(): Response
    {
        return inertia('Users/Edit');
    }

    /**
     * Добавляет нового пользователя
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $this->userService->store($request->validated());

        return redirect()->route('users.index');
    }

    /**
     * Показывает форму редактирования пользователя
     */
    public function edit(User $user): Response
    {
        $user->load('roles');

        return inertia('Users/Edit', [
            'user' => UserResource::make($user),
        ]);
    }

    /**
     * Обновляет данные пользователя
     */
    public function update(int $id, UserRequest $request): RedirectResponse
    {
        $this->userService->update($id, $request->validated());

        return redirect()->route('users.index');
    }

    /**
     * Переключает активность пользователя
     */
    public function toggleActivity(int $id): void
    {
        $this->userService->toggleActivity($id);
    }
}

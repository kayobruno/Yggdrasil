<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ConflictException;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Http\Requests\Filter\FilterRequest;
use App\Http\Resources\Admin\AdminCollection;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Role\Role;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @param FilterRequest $request
     * @return JsonResponse
     */
    public function index(FilterRequest $request)
    {
        $method = $request->perPage == -1 ? 'get' : 'paginate';
        $perPage = $request->perPage == -1 ? '*' : $request->perPage;

        Paginator::currentPageResolver(function () use ($request) {
            return $request->page;
        });

        $users = User::orderBy($request->orderBy, $request->sort)->$method($perPage);
        return $this->createApiResponse(new AdminCollection($users));
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->createApiResponse(new AdminResource($user));
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request)
    {
        $fields = $request->all();
        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);
        $roleAdmin = Role::where('name', 'admin')->first();
        $user->attachRole($roleAdmin);

        return $this->createApiResponse(new AdminResource($user), Response::HTTP_CREATED);
    }

    /**
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        $fields = $request->all();
        if (isset($fields['password'])) {
            $fields['password'] = bcrypt($fields['password']);
        }
        $user->fill($fields)->save();

        return $this->createApiResponse(new AdminResource($user->refresh()));
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws ConflictException
     * @throws UnauthorizedException
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        /** @var User $currentUser */
        if (!$currentUser->hasRole('super-admin')) {
            throw new UnauthorizedException(__('messages.error.unauthorized'));
        }

        if ($currentUser->id === $user->id) {
            throw new ConflictException(__('messages.users.cant_delete_yourself'));
        }
        $user->delete();

        return $this->okButNoContent();
    }
}

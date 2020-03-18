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
     * @OA\Get(path="/users", tags={"Users"}, security={ {"bearer": {}} },
     *     @OA\Parameter(description="Use -1 to get all users", in="path", name="perPage",
     *         @OA\Schema(type="integer", format="int64", default="10")
     *     ),
     *     @OA\Parameter(description="", in="path", name="page",
     *         @OA\Schema(type="integer", format="int64", default="1")
     *     ),
     *     @OA\Parameter(description="", in="path", name="orderBy",
     *         @OA\Schema(type="string", default="id", enum={"id", "name", "email"}),
     *     ),
     *     @OA\Parameter(description="", in="path", name="sort",
     *         @OA\Schema(type="string", default="ASC")
     *     ),
     *     @OA\Response(response="200", description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="items", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="pagination", type="object", @OA\Items(type="object")),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
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
     * @OA\Get(path="/users/{id}", tags={"Users"}, security={ {"bearer": {}} }, description="Find user by ID",
     *     @OA\Parameter(in="path", name="id",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(response="200", description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="createdAt", type="string"),
     *                 @OA\Property(property="updatedAt", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return $this->createApiResponse(new AdminResource($user));
    }

    /**
     * @OA\Post(path="/users", tags={"Users"}, security={ {"bearer": {}} }, description="Create user",
     *     @OA\RequestBody(required=true,
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string", minimum="8"),
     *                 @OA\Property(property="passwordConfirmation", type="string", minimum="8"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="createdAt", type="string"),
     *                 @OA\Property(property="updatedAt", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
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
     * @OA\Put(path="/users/{id}", tags={"Users"}, security={ {"bearer": {}} }, description="Update user",
     *     @OA\Parameter(description="", in="path", name="id",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(required=false,
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string", minimum="8"),
     *                 @OA\Property(property="passwordConfirmation", type="string", minimum="8"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="createdAt", type="string"),
     *                 @OA\Property(property="updatedAt", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
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
     * @OA\Delete(path="/users/{id}", tags={"Users"}, security={ {"bearer": {}} },
     *     @OA\Response(response="204", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
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

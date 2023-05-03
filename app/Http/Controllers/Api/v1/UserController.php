<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected User $repository,
    ) {
    }

    public function index()
    {
        $users = $this->repository->paginate();
        return UserResource::collection($users);
    }

    public function update(StoreUpdateUserRequest $request, string $id)
    {
        $user = $this->repository->findOrFail($id);
        $data = $request->validated();
        if ($request->password)
            $data['password'] = bcrypt($request->password);
        $user->update($data);
        // return response()->json($user);
        return new UserResource($user);
    }

    public function destroy(string $id)
    {
        $user = $this->repository->findOrFail($id);
        $user->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserCreate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate(10));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(UserStoreRequest $request)
    {

        $userService = new UserCreate();
        $newUser = $userService->createUser($request->all());

        return new UserResource($newUser);
    }

    public function show(User $user)
    {
        return view('user.show');
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->save();

        return redirect()->route('user.show', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index');
    }
}

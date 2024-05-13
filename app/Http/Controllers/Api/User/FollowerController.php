<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\FollowerControllerStoreRequest;
use App\Http\Requests\FollowerControllerUpdateRequest;
use App\Models\Follower;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FollowerController extends Controller
{
    public function index(Request $request): Response
    {
        $followers = Follower::all();

        return view('follower.index', compact('followers'));
    }

    public function show(Request $request, Follower $follower): Response
    {
        return view('follower.show', compact('follower'));
    }

    public function store(FollowerControllerStoreRequest $request): Response
    {
        $follower = Follower::create($request->validated());

        return redirect()->route('follower.index');
    }

    public function update(FollowerControllerUpdateRequest $request, Follower $follower): Response
    {
        $follower->save();

        return redirect()->route('follower.show', ['follower' => $follower]);
    }

    public function destroy(Request $request, Follower $follower): Response
    {
        $follower->delete();

        return redirect()->route('follower.index');
    }
}

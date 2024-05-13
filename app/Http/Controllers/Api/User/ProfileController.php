<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\ProfileControllerStoreRequest;
use App\Http\Requests\ProfileControllerUpdateRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): Response
    {
        $profiles = Profile::all();

        return view('profile.index', compact('profiles'));
    }

    public function show(Request $request, Profile $profile): Response
    {
        return view('profile.show', compact('profile'));
    }

    public function store(ProfileControllerStoreRequest $request): Response
    {
        $profile = Profile::create($request->validated());

        return redirect()->route('profile.index');
    }

    public function update(ProfileControllerUpdateRequest $request, Profile $profile): Response
    {
        $profile->save();

        return redirect()->route('profile.show', ['profile' => $profile]);
    }

    public function destroy(Request $request, Profile $profile): Response
    {
        $profile->delete();

        return redirect()->route('profile.index');
    }
}

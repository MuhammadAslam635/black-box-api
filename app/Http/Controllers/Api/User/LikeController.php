<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\LikeControllerStoreRequest;
use App\Http\Requests\LikeControllerUpdateRequest;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LikeController extends Controller
{
    public function index(Request $request): Response
    {
        $likes = Like::all();

        return view('like.index', compact('likes'));
    }

    public function show(Request $request, Like $like): Response
    {
        return view('like.show', compact('like'));
    }

    public function store(LikeControllerStoreRequest $request): Response
    {
        $like = Like::create($request->validated());

        return redirect()->route('like.index');
    }

    public function update(LikeControllerUpdateRequest $request, Like $like): Response
    {
        $like->save();

        return redirect()->route('like.show', ['like' => $like]);
    }

    public function destroy(Request $request, Like $like): Response
    {
        $like->delete();

        return redirect()->route('like.index');
    }
}

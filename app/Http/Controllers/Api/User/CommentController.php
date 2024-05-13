<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\CommentControllerStoreRequest;
use App\Http\Requests\CommentControllerUpdateRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): Response
    {
        $comments = Comment::all();

        return view('comment.index', compact('comments'));
    }

    public function show(Request $request, Comment $comment): Response
    {
        return view('comment.show', compact('comment'));
    }

    public function store(CommentControllerStoreRequest $request): Response
    {
        $comment = Comment::create($request->validated());

        return redirect()->route('comment.index');
    }

    public function update(CommentControllerUpdateRequest $request, Comment $comment): Response
    {
        $comment->save();

        return redirect()->route('comment.show', ['comment' => $comment]);
    }

    public function destroy(Request $request, Comment $comment): Response
    {
        $comment->delete();

        return redirect()->route('comment.index');
    }
}

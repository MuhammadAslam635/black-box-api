<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\ContentControllerStoreRequest;
use App\Http\Requests\ContentControllerUpdateRequest;
use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): Response
    {
        $contents = Content::all();

        return view('content.index', compact('contents'));
    }

    public function show(Request $request, Content $content): Response
    {
        return view('content.show', compact('content'));
    }

    public function store(ContentControllerStoreRequest $request): Response
    {
        $content = Content::create($request->validated());

        return redirect()->route('content.index');
    }

    public function update(ContentControllerUpdateRequest $request, Content $content): Response
    {
        $content->save();

        return redirect()->route('content.show', ['content' => $content]);
    }

    public function destroy(Request $request, Content $content): Response
    {
        $content->delete();

        return redirect()->route('content.index');
    }
}

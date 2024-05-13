<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
final class CommentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $comments = Comment::factory()->count(3)->create();

        $response = $this->get(route('comments.index'));

        $response->assertOk();
        $response->assertViewIs('comment.index');
        $response->assertViewHas('comments');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->get(route('comments.show', $comment));

        $response->assertOk();
        $response->assertViewIs('comment.show');
        $response->assertViewHas('comment');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'store',
            \App\Http\Requests\CommentControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $content = ::factory()->create();
        $comment = $this->faker->text();

        $response = $this->post(route('comments.store'), [
            'user_id' => $user->id,
            'content_id' => $content->id,
            'comment' => $comment,
        ]);

        $comments = Comment::query()
            ->where('user_id', $user->id)
            ->where('content_id', $content->id)
            ->where('comment', $comment)
            ->get();
        $this->assertCount(1, $comments);
        $comment = $comments->first();

        $response->assertRedirect(route('comment.index'));
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'update',
            \App\Http\Requests\CommentControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_saves_and_redirects(): void
    {
        $comment = Comment::factory()->create();
        $user = User::factory()->create();
        $content = ::factory()->create();
        $comment = $this->faker->text();

        $response = $this->put(route('comments.update', $comment), [
            'user_id' => $user->id,
            'content_id' => $content->id,
            'comment' => $comment,
        ]);

        $comments = Comment::query()
            ->where('user_id', $user->id)
            ->where('content_id', $content->id)
            ->where('comment', $comment)
            ->get();
        $this->assertCount(1, $comments);
        $comment = $comments->first();

        $response->assertRedirect(route('comment.show', ['comment' => $comment]));
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comments.destroy', $comment));

        $response->assertRedirect(route('comment.index'));

        $this->assertModelMissing($comment);
    }
}

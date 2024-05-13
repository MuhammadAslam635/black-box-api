<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LikeController
 */
final class LikeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $likes = Like::factory()->count(3)->create();

        $response = $this->get(route('likes.index'));

        $response->assertOk();
        $response->assertViewIs('like.index');
        $response->assertViewHas('likes');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $like = Like::factory()->create();

        $response = $this->get(route('likes.show', $like));

        $response->assertOk();
        $response->assertViewIs('like.show');
        $response->assertViewHas('like');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\LikeController::class,
            'store',
            \App\Http\Requests\LikeControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $content = ::factory()->create();

        $response = $this->post(route('likes.store'), [
            'user_id' => $user->id,
            'content_id' => $content->id,
        ]);

        $likes = Like::query()
            ->where('user_id', $user->id)
            ->where('content_id', $content->id)
            ->get();
        $this->assertCount(1, $likes);
        $like = $likes->first();

        $response->assertRedirect(route('like.index'));
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\LikeController::class,
            'update',
            \App\Http\Requests\LikeControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_saves_and_redirects(): void
    {
        $like = Like::factory()->create();
        $user = User::factory()->create();
        $content = ::factory()->create();

        $response = $this->put(route('likes.update', $like), [
            'user_id' => $user->id,
            'content_id' => $content->id,
        ]);

        $likes = Like::query()
            ->where('user_id', $user->id)
            ->where('content_id', $content->id)
            ->get();
        $this->assertCount(1, $likes);
        $like = $likes->first();

        $response->assertRedirect(route('like.show', ['like' => $like]));
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $like = Like::factory()->create();

        $response = $this->delete(route('likes.destroy', $like));

        $response->assertRedirect(route('like.index'));

        $this->assertModelMissing($like);
    }
}

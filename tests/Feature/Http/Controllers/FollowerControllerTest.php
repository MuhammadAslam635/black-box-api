<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Follower;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FollowerController
 */
final class FollowerControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $followers = Follower::factory()->count(3)->create();

        $response = $this->get(route('followers.index'));

        $response->assertOk();
        $response->assertViewIs('follower.index');
        $response->assertViewHas('followers');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $follower = Follower::factory()->create();

        $response = $this->get(route('followers.show', $follower));

        $response->assertOk();
        $response->assertViewIs('follower.show');
        $response->assertViewHas('follower');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FollowerController::class,
            'store',
            \App\Http\Requests\FollowerControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $follower_id = $this->faker->word();
        $following_id = $this->faker->word();

        $response = $this->post(route('followers.store'), [
            'follower_id' => $follower_id,
            'following_id' => $following_id,
        ]);

        $followers = Follower::query()
            ->where('follower_id', $follower_id)
            ->where('following_id', $following_id)
            ->get();
        $this->assertCount(1, $followers);
        $follower = $followers->first();

        $response->assertRedirect(route('follower.index'));
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FollowerController::class,
            'update',
            \App\Http\Requests\FollowerControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_saves_and_redirects(): void
    {
        $follower = Follower::factory()->create();
        $follower_id = $this->faker->word();
        $following_id = $this->faker->word();

        $response = $this->put(route('followers.update', $follower), [
            'follower_id' => $follower_id,
            'following_id' => $following_id,
        ]);

        $followers = Follower::query()
            ->where('follower_id', $follower_id)
            ->where('following_id', $following_id)
            ->get();
        $this->assertCount(1, $followers);
        $follower = $followers->first();

        $response->assertRedirect(route('follower.show', ['follower' => $follower]));
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $follower = Follower::factory()->create();

        $response = $this->delete(route('followers.destroy', $follower));

        $response->assertRedirect(route('follower.index'));

        $this->assertModelMissing($follower);
    }
}

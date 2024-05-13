<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProfileController
 */
final class ProfileControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $profiles = Profile::factory()->count(3)->create();

        $response = $this->get(route('profiles.index'));

        $response->assertOk();
        $response->assertViewIs('profile.index');
        $response->assertViewHas('profiles');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->get(route('profiles.show', $profile));

        $response->assertOk();
        $response->assertViewIs('profile.show');
        $response->assertViewHas('profile');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProfileController::class,
            'store',
            \App\Http\Requests\ProfileControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $profile_picture = $this->faker->word();
        $bio = $this->faker->text();

        $response = $this->post(route('profiles.store'), [
            'user_id' => $user->id,
            'profile_picture' => $profile_picture,
            'bio' => $bio,
        ]);

        $profiles = Profile::query()
            ->where('user_id', $user->id)
            ->where('profile_picture', $profile_picture)
            ->where('bio', $bio)
            ->get();
        $this->assertCount(1, $profiles);
        $profile = $profiles->first();

        $response->assertRedirect(route('profile.index'));
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProfileController::class,
            'update',
            \App\Http\Requests\ProfileControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_saves_and_redirects(): void
    {
        $profile = Profile::factory()->create();
        $user = User::factory()->create();
        $profile_picture = $this->faker->word();
        $bio = $this->faker->text();

        $response = $this->put(route('profiles.update', $profile), [
            'user_id' => $user->id,
            'profile_picture' => $profile_picture,
            'bio' => $bio,
        ]);

        $profiles = Profile::query()
            ->where('user_id', $user->id)
            ->where('profile_picture', $profile_picture)
            ->where('bio', $bio)
            ->get();
        $this->assertCount(1, $profiles);
        $profile = $profiles->first();

        $response->assertRedirect(route('profile.show', ['profile' => $profile]));
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->delete(route('profiles.destroy', $profile));

        $response->assertRedirect(route('profile.index'));

        $this->assertModelMissing($profile);
    }
}

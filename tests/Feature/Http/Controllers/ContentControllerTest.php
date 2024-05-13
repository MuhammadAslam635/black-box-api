<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ContentController
 */
final class ContentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $contents = Content::factory()->count(3)->create();

        $response = $this->get(route('contents.index'));

        $response->assertOk();
        $response->assertViewIs('content.index');
        $response->assertViewHas('contents');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $content = Content::factory()->create();

        $response = $this->get(route('contents.show', $content));

        $response->assertOk();
        $response->assertViewIs('content.show');
        $response->assertViewHas('content');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ContentController::class,
            'store',
            \App\Http\Requests\ContentControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $type = $this->faker->randomElement(/** enum_attributes **/);
        $title = $this->faker->sentence(4);
        $description = $this->faker->text();
        $file_path = $this->faker->word();

        $response = $this->post(route('contents.store'), [
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'file_path' => $file_path,
        ]);

        $contents = Content::query()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->where('title', $title)
            ->where('description', $description)
            ->where('file_path', $file_path)
            ->get();
        $this->assertCount(1, $contents);
        $content = $contents->first();

        $response->assertRedirect(route('content.index'));
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ContentController::class,
            'update',
            \App\Http\Requests\ContentControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_saves_and_redirects(): void
    {
        $content = Content::factory()->create();
        $user = User::factory()->create();
        $type = $this->faker->randomElement(/** enum_attributes **/);
        $title = $this->faker->sentence(4);
        $description = $this->faker->text();
        $file_path = $this->faker->word();

        $response = $this->put(route('contents.update', $content), [
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'file_path' => $file_path,
        ]);

        $contents = Content::query()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->where('title', $title)
            ->where('description', $description)
            ->where('file_path', $file_path)
            ->get();
        $this->assertCount(1, $contents);
        $content = $contents->first();

        $response->assertRedirect(route('content.show', ['content' => $content]));
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $content = Content::factory()->create();

        $response = $this->delete(route('contents.destroy', $content));

        $response->assertRedirect(route('content.index'));

        $this->assertModelMissing($content);
    }
}

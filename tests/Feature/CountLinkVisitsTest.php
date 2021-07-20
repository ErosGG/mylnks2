<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountLinkVisitsTest extends TestCase
{
    use RefreshDatabase;

    public function test_link_views_counter_increments_when_is_visited()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->assertDatabaseHas("users", [
            "id" => $user->id,
        ]);
        $this->assertDatabaseHas("links", [
            "id" => $link->id,
            "views" => 0,
        ]);
        $this->get("/count/{$link->id}");
        $this->assertDatabaseHas("links", [
            "id" => $link->id,
            "views" => 1,
        ]);
    }

    public function test_redirection_to_requested_url_after_counting_link_visit()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->assertDatabaseHas("users", [
            "id" => $user->id,
        ]);
        $this->assertDatabaseHas("links", [
            "id" => $link->id,
        ]);
        $this->get("/count/{$link->id}")
            ->assertRedirect($link->url);
    }

    public function test_shows_404_error_if_requested_link_does_not_exist()
    {
        $this->get("/count/7")
            ->assertStatus(404);
    }
}

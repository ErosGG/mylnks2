<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicUserLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_links_page_cannot_be_rendered_if_the_user_does_not_exist()
    {
        $this->get("/noexistinguser")
            ->assertStatus(404);
    }

    public function test_public_user_links_page_can_be_rendered_if_the_user_exists()
    {
        $userA = User::factory()
            ->create([
                "nick" => "usertest1",
            ]);
        $linkA1 = Link::factory()
            ->create([
                "user_id" => $userA->id,
            ]);
        $linkA2 = Link::factory()
            ->create([
                "user_id" => $userA->id,
                "title" => "NASA",
            ]);
        $userB = User::factory()
            ->create([
                "nick" => "usertest2",
            ]);
        $linkB1 = Link::factory()
            ->create([
                "user_id" => $userB->id,
            ]);
        $linkB2 = Link::factory()
            ->create([
                "user_id" => $userB->id,
                "title" => "HUBBLE SITE"
            ]);
        $this->assertDatabaseHas("users", [
            "nick" => [
                "usertest1",
                "usertest2",
            ],
        ]);
        $this->assertDatabaseHas("links", [
            "id" => [
                $linkA1->id,
                $linkA2->id,
                $linkB1->id,
                $linkB2->id,
            ]
        ]);
        $this->get("/{$userA->nick}")
            ->assertViewIs("public-user-links")
            ->assertStatus(200)
            ->assertSee([
                $linkA1->title,
                "NASA",
            ])
            ->assertDontSee([
                $linkB1->title,
                "HUBBLE SITE"
            ]);
    }
}

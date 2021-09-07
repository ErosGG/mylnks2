<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestoreLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_restore_a_link()
    {
        User::factory()->create();
        $link = Link::factory()->create([
            "deleted_at" => now(),
        ]);
        $this->patch("/links/$link->id/restore");
        $this->assertSoftDeleted($link);
    }

    /** @test */
    public function guest_users_are_redirected_to_login_screen()
    {
        User::factory()->create();
        $link = Link::factory()->create([
            "deleted_at" => now(),
        ]);
        $this->patch("/links/$link->id/restore")
            ->assertStatus(302)
            ->assertRedirect("/login");
    }

    /** @test */
    public function users_who_do_not_own_the_link_cannot_restore_it()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $userA->id,
            "deleted_at" => now(),
        ]);
        $this->actingAs($userB)->patch("/links/$link->id/restore");
        $this->assertSoftDeleted($link);
    }

    /** @test */
    public function link_owner_user_can_restore_it()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            "deleted_at" => now(),
        ]);
        $this->actingAs($user)->patch("/links/$link->id/restore");
        $this->assertDatabaseHas("links", [
            "id" => $link->id,
            "deleted_at" => null,
        ]);
    }

    /** @test */
    public function cannot_restore_a_link_if_it_has_not_been_already_deleted()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->patch("/links/$link->id/restore")
            ->assertStatus(301);
    }

    /** @test */
    public function shows_404_error_if_requested_link_does_not_exist()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch("links/300/restore")
            ->assertNotFound();  // assertStatus(404)
    }
}

<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_destroy_a_link()
    {
        User::factory()->create();
        $link = Link::factory()->create([
            "deleted_at" => now(),
        ]);
        $this->delete("/links/$link->id/destroy");
        $this->assertSoftDeleted($link);
    }

    /** @test */
    public function guest_users_are_redirected_to_login_screen()
    {
        User::factory()->create();
        $link = Link::factory()->create([
            "deleted_at" => now(),
        ]);
        $this->delete("/links/$link->id/destroy")
            ->assertStatus(302)
            ->assertRedirect("/login");
    }

    /** @test */
    public function users_who_do_not_own_the_link_cannot_destroy_it()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $userA->id,
            "deleted_at" => now(),
        ]);
        $this->actingAs($userB)->delete("/links/$link->id/destroy");
        $this->assertSoftDeleted($link);
    }

    /** @test */
    public function link_owner_user_can_destroy_it()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            "deleted_at" => now(),
        ]);
        $this->actingAs($user)->delete("/links/$link->id/destroy");
        $this->assertDatabaseMissing("links", [
            "id" => $link->id,
        ]);
    }

    /** @test */
    public function cannot_destroy_a_link_if_it_has_not_been_already_deleted()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->patch("/links/$link->id/restore")
            ->assertStatus(301);
        $this->assertDatabaseHas("links", [
            "id" => $link->id,
        ]);
    }

    /** @test */
    public function shows_404_error_if_requested_link_does_not_exist()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->delete("links/300/destroy")
            ->assertNotFound();  // assertStatus(404)
    }
}

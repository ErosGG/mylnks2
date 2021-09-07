<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_delete_a_link()
    {
        User::factory()->create();
        $link = Link::factory()->create();
        $this->patch("/links/$link->id/delete");
        $this->assertFalse($link->trashed());
        $this->assertDatabaseHas("links", [
            "title" => $link->title,
            "id" => $link->id,
            "deleted_at" => null,
        ]);
    }

    /** @test */
    public function guest_users_are_redirected_to_login_screen()
    {
        User::factory()->create();
        $link = Link::factory()->create();
        $this->patch("/links/$link->id/delete")
            ->assertStatus(302)
            ->assertRedirect("/login");
    }

    /** @test */
    public function users_who_do_not_own_the_link_cannot_delete_it()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $userA->id,
        ]);
        $this->actingAs($userB)->patch("/links/$link->id/delete");
        $this->assertFalse($link->trashed());
        $this->assertDatabaseHas("links", [
            "title" => $link->title,
            "id" => $link->id,
            "deleted_at" => null,
        ]);
    }

    /** @test */
    public function link_owner_user_can_delete_it()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->patch("/links/$link->id/delete");
        $this->assertSoftDeleted("links", [
            "title" => $link->title,
            "id" => $link->id,
        ]);
    }

    /** @test */
    public function cannot_delete_a_link_if_it_has_been_already_deleted()
    {
        //$this->markTestSkipped("REVISAR: No aconsegueixo que el test funcioni correctament");
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->patch("/links/$link->id/delete")
            ->assertStatus(302);
        $this->assertSoftDeleted("links", [
            "title" => $link->title,
            "id" => $link->id,
        ]);
        $this->actingAs($user)->patch("/links/$link->id/delete")
            ->assertStatus(301);
    }

    /** @test */
    public function shows_404_error_if_requested_link_does_not_exist()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch("links/300/delete")
            ->assertNotFound();  // assertStatus(404)
    }
}

<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_link_owner_user_can_open_it_on_link_editor()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $user->id,
        ]);
        $this->actingAs($user)->get("/links/$link->id/edit/")
            ->assertStatus(200);
    }

    public function test_authenticated_users_who_do_not_own_the_link_are_redirected_to_the_dashboard()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $userA->id,
        ]);
        $this->actingAs($userB)->get("/links/$link->id/edit/")
            ->assertRedirect("/dashboard");
    }

    public function test_guest_users_are_redirected_to_login_screen()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $user->id,
        ]);
        $this->get("/links/$link->id/edit/")
            ->assertRedirect("/login");
    }
}

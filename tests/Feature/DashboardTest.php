<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_is_rendered_if_a_user_is_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertStatus(200);
    }

    public function test_redirect_to_login_if_a_user_is_not_authenticated_when_tries_to_access_the_dashboard()
    {
        $this->assertGuest()
            ->get("/dashboard")
            ->assertRedirect("/login");
    }

    public function test_shows_welcoming_user_message_when_dashboard_loads()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSee([
                "Hi, {$user->name}",
                e("logged in!")
            ]);
    }

    public function test_shows_no_links_to_display_message_if_the_user_has_no_links()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSee("No hi ha links per a mostrar");
    }

    public function test_shows_only_the_user_links_if_any()
    {
        $userA = User::factory()->create(["id" => 1]);
        $userB = User::factory()->create(["id" => 2]);
        $linksA = Link::factory()->times(3)->create(["user_id" => $userA->id]);
        $linksB = Link::factory()->times(3)->create(["user_id" => $userB->id]);
        Auth::login($userA);
        $this->get("/dashboard")
            ->assertSee([$linksA[0]->title, $linksA[1]->title, $linksA[2]->title])
            ->assertDontSee([$linksB[0]->title, $linksB[1]->title, $linksB[2]->title]);
    }
}

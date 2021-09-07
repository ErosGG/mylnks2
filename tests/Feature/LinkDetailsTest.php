<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkDetailsTest extends TestCase
{
    use refreshDatabase;

    /** @test */
    public function guest_users_are_redirected_to_login_screen()  // guest_users_cannot_see_link_details()
    {
        User::factory()->create();
        $link = Link::factory()->create();
        $this->get("/links/$link->id")
            ->assertStatus(302)
            ->assertRedirect("/login");
    }

    /** @test */
    public function authenticated_users_who_do_not_own_the_link_are_redirected_to_the_dashboard()  // users_who_do_not_own_the_link_cannot_see_its_details()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $userA->id,
        ]);
        $this->actingAs($userB)->get("/links/$link->id")
            ->assertStatus(301)
            ->assertRedirect("/dashboard");
    }

    /** @test */
    public function details_page_shows_link_details_and_link_owner_user_can_view_them()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->get("/links/$link->id")
            ->assertOk()  // assertStatus(200)
            ->assertSee([
                $link->title,
                $link->url,
                $link->views, // Com assegurar-se que sigui del nombre de visites i no de qualsevol altra cosa?
            ]);
    }

    /** @test */
    public function shows_404_error_if_requested_link_does_not_exist()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get("links/300/edit")
            ->assertNotFound();  // assertStatus(404)
    }
}

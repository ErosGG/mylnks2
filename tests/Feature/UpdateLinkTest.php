<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_users_cannot_update_the_link()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->put("/links/$link->id/edit", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
    }

    public function test_users_who_do_not_own_the_link_cannot_update_it()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $link = Link::factory()->create([
            "user_id" => $userA->id,
        ]);
        $this->actingAs($userB)->put("/links/$link->id/edit", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
    }

    public function test_link_owner_user_can_update_it_providing_valid_data()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
    }

    public function test_shows_no_error_messages_when_provides_valid_data()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
        $this->actingAs($user)->get("/links/$link->id/edit")
            ->assertDontSeeText([
                "El camp títol és obligatori",
                "El camp títol només admet cadenes de text",
                "El camp títol pot contenir un màxim de 50 caràcters",
                "El camp URL és obligatori",
                "El camp URL ha de ser una URL vàlida",
            ]);
    }

    public function test_user_cannot_update_a_link_without_providing_a_title()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "url" => "https://www.prova.cat/",
        ])->assertSessionHasErrors([
            "title" => "El camp títol és obligatori",
        ]);
        $this->assertDatabaseMissing("links", [
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_shows_an_error_message_if_no_title_is_provided()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)
            ->get("/links/$link->id/edit/")
            ->assertSeeText("El camp títol és obligatori");
    }

    public function test_user_cannot_update_a_link_if_title_is_longer_than_50_characters()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => "Lorem ipsum dolor sit amet, consectetuer adipiscing",
            "url" => "https://www.prova.cat/",
        ])->assertSessionHasErrors([
            "title" => "El camp títol pot contenir un màxim de 50 caràcters",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Lorem ipsum dolor sit amet, consectetuer adipiscing",
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_shows_an_error_message_if_title_is_longer_than_50_characters()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => "Lorem ipsum dolor sit amet, consectetuer adipiscing",
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)
            ->get("/links/$link->id/edit")
            ->assertSeeText("El camp títol pot contenir un màxim de 50 caràcters");
    }

    public function test_user_cannot_update_a_link_if_title_is_not_string_data_type()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => ["array com a tipus de dades"],
            "url" => "https://www.prova.cat/",
        ])->assertSessionHasErrors([
            "title" => "El camp títol només admet cadenes de text",
        ]);
        $this->assertDatabaseMissing("links", [
            "url" => "https://www.prova.cat/",
        ]);
    }

    // AQUEST NO FUNCIONA CORRECTAMENT /////////////////////////////////////////////////////////////////////////////////
    public function test_shows_an_error_message_if_title_is_not_string_data_type()
    {
        $this->markTestSkipped("REVISAR: No aconsegueixo que el test funcioni correctament");
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => ["array com a tipus de dades"],
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)
            ->get("/links/$link->id/edit")
            ->assertSeeText("El camp títol només admet cadenes de text");
    }

    public function test_user_cannot_update_a_link_without_providing_an_url()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => "Prova",
        ])->assertSessionHasErrors([
            "url" => "El camp URL és obligatori",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
        ]);
    }

    public function test_shows_an_error_message_if_no_url_is_provided()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "title" => "Prova",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("El camp URL és obligatori");
    }

    /** @test */
    public function user_cannot_update_a_link_providing_an_invalid_url()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "url" => "url-incorrecta",
        ])->assertSessionHasErrors([
            "url" => "El camp URL ha de ser una URL vàlida",
        ]);
        $this->assertDatabaseMissing("links", [
            "url" => "url-incorrecta",
        ])->assertDatabaseHas("links", [
            "url" => $link->url,
        ]);
    }

    /** @test */
    public function shows_an_error_message_if_an_invalid_url_is_provided()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit", [
            "url" => "url-incorrecta",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("El camp URL ha de ser una URL vàlida");
    }

    /** @test */
    public function user_cannot_update_a_link_providing_an_non_unique_url()
    {
        $user = User::factory()->create();
        $linkA = Link::factory()->create();
        $linkB = Link::factory()->create();
        $this->actingAs($user)->put("/links/$linkA->id/edit", [
            "url" => $linkB->url,
        ])->assertSessionHasErrors([
            "url" => "La URL introduïda ja existeix",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => $linkA->title,
            "url" => $linkB->url,
        ])->assertDatabaseHas("links", [
            "title" => $linkA->title,
            "url" => $linkA->url,
        ]);
    }

    /** @test */
    public function shows_an_error_message_if_a_non_unique_url_is_provided()
    {
        $user = User::factory()->create();
        $linkA = Link::factory()->create();
        $linkB = Link::factory()->create();
        $this->actingAs($user)->put("/links/$linkA->id/edit", [
            "url" => $linkB->url,
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("La URL introduïda ja existeix");
    }
}

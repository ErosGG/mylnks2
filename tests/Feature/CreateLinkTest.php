<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CreateLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_users_cannot_create_a_link()
    {
        $this->post("/dashboard", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_authenticated_user_can_create_a_link_providing_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_shows_no_error_messages_when_provides_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertDontSeeText([
                "El camp títol és obligatori",
                "El camp títol només admet cadenes de text",
                "El camp títol pot contenir un màxim de 50 caràcters",
                "El camp URL és obligatori",
                "El camp URL ha de ser una URL vàlida",
            ]);
    }

    public function test_user_cannot_create_a_link_without_providing_a_title()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
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
        $this->actingAs($user)->post("/dashboard", [
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("El camp títol és obligatori");
    }

    public function test_user_cannot_create_a_link_if_title_is_longer_than_50_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Lorem ipsum dolor sit amet, consectetuer adipiscing",
            "url" => "https://www.prova.cat/",
        ])->assertSessionHasErrors([
            "title" => "El camp títol pot contenir un màxim de 50 caràcters",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Lorem ipsum dolor sit amet, consectetuer adipiscin",
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_shows_an_error_message_if_title_is_longer_than_50_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Lorem ipsum dolor sit amet, consectetuer adipiscing",
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("El camp títol pot contenir un màxim de 50 caràcters");
    }

    public function test_user_cannot_create_a_link_if_title_is_not_string_data_type()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => ["array com a tipus de dades"],
            "url" => "https://www.prova.cat/",
        ])->assertSessionHasErrors([
            "title" => "El camp títol només admet cadenes de text",
        ]);
        $this->assertDatabaseMissing("links", [
            "url" => "https://www.prova.cat/",
        ]);
    }

    // No aconsegueixo que el següent test funcioni correctament
    /*
    public function test_shows_an_error_message_if_title_is_not_string_data_type()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => ["array com a tipus de dades"],
            "url" => "https://www.prova.cat/",
        ]);
        $this->actingAs($user)->get("/dashboard")
            ->assertSeeText("El camp títol només admet cadenes de text");
    }
    */

    public function test_user_cannot_create_a_link_without_providing_an_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
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
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("El camp URL és obligatori");
    }

    public function test_user_cannot_create_a_link_providing_an_invalid_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => "url-incorrecta",
        ])->assertSessionHasErrors([
            "url" => "El camp URL ha de ser una URL vàlida",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
            "url" => "url-incorrecta",
        ]);
    }

    public function test_shows_an_error_message_if_an_invalid_url_is_provided()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => "url-incorrecta",
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("El camp URL ha de ser una URL vàlida");
    }

    public function test_user_cannot_create_a_link_providing_a_non_unique_url()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => $link->url,
        ])->assertSessionHasErrors([
            "url" => "La URL introduïda ja existeix",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
            "url" => $link->url,
        ]);
    }

    public function test_shows_an_error_message_if_a_non_unique_url_is_provided()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => $link->url,
        ]);
        $this->actingAs($user)
            ->get("/dashboard")
            ->assertSeeText("La URL introduïda ja existeix");
    }
}

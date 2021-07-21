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

    public function test_only_authenticated_users_can_create_a_link()
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

    public function test_user_can_create_a_link_providing_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ]);
        $this->assertDatabaseHas("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_user_cannot_create_a_link_without_providing_a_title()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "url" => "https://www.prova.cat/",
        ]);
        $this->assertDatabaseMissing("links", [
            "url" => "https://www.prova.cat/",
        ]);
    }

    public function test_user_cannot_create_a_link_without_providing_an_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
        ]);
    }

    public function test_user_cannot_create_a_link_providing_an_invalid_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post("/dashboard", [
            "title" => "Prova",
            "url" => "url-incorrecta",
        ]);
        $this->assertDatabaseMissing("links", [
            "title" => "Prova",
            "url" => "url-incorrecta",
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLinkTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
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
}

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

    public function test_link_owner_user_can_update_it_providing_valid_data()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();
        $this->actingAs($user)->put("/links/$link->id/edit/", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas("links", [
            "title" => "Prova",
            "url" => "https://www.prova.cat/"
        ]);
    }
}

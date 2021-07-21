<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;

class PublicUserLinksPageController extends Controller
{
    public function showUserPublicLinks($nick)
    {
        $user = User::firstWhere("nick", "=", $nick);
        if (! $user) abort(404);
        $links = $user->links()->get();
        return view("public-user-links")
            ->with("links", $links);
    }

    public function countLinkVisit(Link $link)
    {
        $link->views++;
        $link->save();
        return redirect($link->url);
    }
}

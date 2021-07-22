<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard')
            ->with([
                "user" => $user,
                "links" => $user->links()->get()
            ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
                "title" => ["required", "string", "max:50"],
                "url" => ["required", "url"],
            ], [
                "title.required" => "El camp títol és obligatori",
                "title.string" => "El camp títol només admet cadenes de text",
                "title.max" => "El camp títol pot contenir un màxim de 50 caràcters",
                "url.required" => "El camp URL és obligatori",
                "url.url" => "El camp URL ha de ser una URL vàlida",
        ]);
        $data["url"] = strtolower($data["url"]);
        Link::create([
            "user_id" => $user->id,
            "title" => $data["title"],
            "url" => $data["url"],
        ]);
        return view("dashboard")
            ->with([
                "user" => $user,
                "links" => $user->links()->get(),
            ]);
    }

    public function editor(Link $link)
    {
        if (Gate::denies("access-link-editor", $link)) {
            $user = Auth::user();
            return redirect()
                ->route("dashboard.index")
                ->with([
                    "user" => $user,
                    "links" => $user->links()->get(),
                ]);
        }
        return view("edit-link")->with("link", $link);
    }

    public function update(Request $request, Link $link)
    {
        if (Auth::user()->can("update", $link)) {
            dd("Controlador: SÍ pot actualitzar el post");
        } else {
            dd("Controlador: NO pot actualitzar el post");
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

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
                "url" => [
                    "required",
                    "url",
                    Rule::unique("links", "url")
                    ->where("user_id", $user->id)
                ],
            ], [
                "title.required" => "El camp títol és obligatori",
                "title.string" => "El camp títol només admet cadenes de text",
                "title.max" => "El camp títol pot contenir un màxim de 50 caràcters",
                "url.required" => "El camp URL és obligatori",
                "url.url" => "El camp URL ha de ser una URL vàlida",
                "url.unique" => "La URL introduïda ja existeix",
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

    public function details(Link $link)
    {
        if (Gate::denies("access-link-details", $link)) {
            //return redirect()->route("dashboard.index");
            return redirect("/dashboard", 301);
        }
        return view("link-details")->with("link", $link);
        /*
        return view("link-details")
            ->with("link", $link);
        */
    }

    public function editor(Link $link)
    {
        if (Gate::denies("access-link-editor", $link)) {
            return redirect()->route("dashboard.index");
        }
        return view("edit-link")->with("link", $link);
    }

    public function update(Request $request, Link $link)
    {
        $user = $request->user();
        if ($user->can("update", $link)) {
            $data = $request->validate([
                "title" => ["required", "string", "max:50"],
                "url" => [
                    "required",
                    "url",
                    Rule::unique("links", "url")
                        ->where("user_id", $user->id)
                        ->ignore($link->id)
                ],
            ], [
                "title.required" => "El camp títol és obligatori",
                "title.string" => "El camp títol només admet cadenes de text",
                "title.max" => "El camp títol pot contenir un màxim de 50 caràcters",
                "url.required" => "El camp URL és obligatori",
                "url.url" => "El camp URL ha de ser una URL vàlida",
                "url.unique" => "La URL introduïda ja existeix",
            ]);
            $data["url"] = strtolower($data["url"]);
            $link->update($data);
            return redirect()->route("dashboard.details", ["link" => $link]);
        }
        return redirect()->route("dashboard.index");
    }
}

<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicUserLinksPageController;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, "index"])
    ->middleware(['auth'])
    ->name("dashboard.index");

Route::get("/paperera", [DashboardController::class, "bin"])
    ->middleware("auth")
    ->name("dashboard.bin");

Route::post("/dashboard", [DashboardController::class, "create"])
    ->middleware(["auth"])
    ->name("dashboard.create");

Route::get("/links/{link}", [DashboardController::class, "details"])
    ->middleware(["auth"])
    ->name("dashboard.details");

Route::get("/links/{link}/edit", [DashboardController::class, "editor"])
    ->middleware(["auth"])
    ->name("dashboard.editor");

Route::put("/links/{link}/edit", [DashboardController::class, "update"])
    ->middleware(["auth"])
    ->name("dashboard.update");

Route::patch("/links/{id}/delete", [DashboardController::class, "delete"])
    ->middleware(["auth"])
    ->name("dashboard.delete");

Route::patch("/links/{id}/restore", [DashboardController::class, "restore"])
    ->middleware("auth")
    ->name("dashboard.restore");

Route::delete("/links/{id}/destroy", [DashboardController::class, "destroy"])
    ->middleware(["auth"])
    ->name("dashboard.destroy");

Route::get("/count/{link}", [PublicUserLinksPageController::class, "countLinkVisit"])
    ->name("count.link.visits");

require __DIR__.'/auth.php';

Route::get("/{nick}", [PublicUserLinksPageController::class, "showUserPublicLinks"])
    ->name("show.public.links");

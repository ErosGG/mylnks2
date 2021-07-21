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

Route::post("/dashboard", [DashboardController::class, "create"])
    ->middleware(["auth"])
    ->name("dashboard.create");

/*
 *
 * BLOC PROVISIONAL
 *
 */

Route::delete("/links/{link}/", [DashboardController::class, "delete"])
    ->middleware(["auth"])
    ->name("dashboard.delete");

Route::get("/links/{link}/", [DashboardController::class, "details"])
    ->middleware(["auth"])
    ->name("dashboard.details");

Route::get("/links/{link}/edit/", [DashboardController::class, "edit"])
    ->middleware(["auth"])
    ->name("dashboard.edit");

/*
 *
 * FI BLOC PROVISIONAL
 *
 */

Route::get("/count/{link}", [PublicUserLinksPageController::class, "countLinkVisit"])
    ->name("count.link.visits");

require __DIR__.'/auth.php';

Route::get("/{nick}", [PublicUserLinksPageController::class, "showUserPublicLinks"])
    ->name("show.public.links");

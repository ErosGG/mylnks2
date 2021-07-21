<?php

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

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard')
        ->with([
            "user" => $user,
            "links" => $user->links()->get()
        ]);
})->middleware(['auth'])
    ->name('dashboard');

/*
 *
 * BLOC PROVISIONAL
 *
 */

Route::post("/dashboard", [DashboardController::class, "create"])
    ->middleware(["auth"])
    ->name("link.create");

Route::delete("/links/{link}/", [DashboardController::class, "delete"])
    ->middleware(["auth"])
    ->name("link.delete");

Route::get("/links/{link}/", [DashboardController::class, "details"])
    ->middleware(["auth"])
    ->name("link.details");

Route::get("/links/{link}/edit/", [DashboardController::class, "edit"])
    ->middleware(["auth"])
    ->name("link.edit");

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

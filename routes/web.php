<?php

use App\Http\Controllers\{
    UserController,
    ClientController,
    ProjectController
};
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get("/auth/redirect/google", function () {
    return Socialite::driver('google')->redirect();
})->name('google');

Route::get("/auth/callback/google", function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::updateorCreate(
        [
            'email' => $googleUser->email,
        ],
        [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
        ]
    );

    Auth::login($user);

    return redirect('/home');
});

Route::get("/auth/redirect", function () {
    return Socialite::driver('github')->redirect();
})->name('github');

Route::get("/auth/callback", function () {
    $githubUser = Socialite::driver('github')->stateless()->user();

    $user = \App\Models\User::updateOrCreate([
        'email' => $githubUser->email,
    ], [
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);

    Auth::login($user);
    return redirect('/home');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', fn () => view('profile'))->name('profile');
    Route::middleware('isAdmin')->group(function () {
        Route::post('/recycle/tasks/trash/{project}', [ProjectController::class, 'restore'])->name('project.restore')->withTrashed();
        Route::get('/recycle/tasks/trash', [ProjectController::class, 'trash'])->name('project.trash')->withTrashed();
        Route::get('/recycle/trash', [ClientController::class, 'trash'])->name('clients.trash')->withTrashed();
        Route::post('/recycle/trash/{client}', [ClientController::class, 'restore'])->name('clients.restore')->withTrashed();
        Route::post('/notifications/{id}', [\App\Http\Controllers\HomeController::class, 'readNotifications'])->name('notification.read');
        Route::get('/notifications', [\App\Http\Controllers\HomeController::class, 'notifications'])->name('notification.index');
        Route::get('/tasks', [ProjectController::class, 'index'])->name('tasks.index');
        Route::get('/recycle', [UserController::class, 'trash'])->name('users.trash');
        Route::post('/recycle/{user}/restore', [UserController::class, 'restore'])->name('users.recycle')->withTrashed();
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::resource('/project', ProjectController::class)->except('index');
        Route::resource('/clients', ClientController::class)->except('show');
        Route::resource('/users', UserController::class)->except('show');
    });
});

Auth::routes(
    [
        'verify' => true
    ]
);

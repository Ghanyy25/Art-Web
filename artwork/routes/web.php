<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

// Controller Publik
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ArtworkDetailController;
use App\Http\Controllers\CreatorProfileController;
use App\Http\Controllers\ChallengeController; // Controller Challenge Publik

// Controller Member (Auth)
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\ChallengeSubmissionController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileUpdateController;

// Controller Admin
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ModerationController;

// Controller Curator
use App\Http\Controllers\Curator\ChallengeController as CuratorChallengeController;
use App\Http\Controllers\Curator\SubmissionController as CuratorSubmissionController;


/*
|--------------------------------------------------------------------------
| Rute Publik (Bisa diakses Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/artwork/{id}', [ArtworkDetailController::class, 'show'])->name('artworks.show');
Route::get('/creator/{id}', [CreatorProfileController::class, 'show'])->name('profile.show');
Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
Route::get('/challenge/{slug}', [ChallengeController::class, 'show'])->name('challenges.show');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi (Login, Register, dll)
|--------------------------------------------------------------------------
*/
// Dashboard (akan diarahkan oleh HomeController)
Route::get('/dashboard', [HomeController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Rute Member (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/edit', [ProfileUpdateController::class, 'edit'])->name('profile.editprofile');
    Route::post('/profile/editprofile', [ProfileUpdateController::class, 'update'])->name('profile.store');

    // My Artworks (CRUD)
    Route::resource('artworks', ArtworkController::class)->except(['show']);

    // My Favorites (Galeri)
    Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // Aksi Interaksi
    Route::post('/like/artwork/{artworkId}', [LikeController::class, 'toggle'])->name('like.toggle');
    Route::post('/favorite/artwork/{artworkId}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
    Route::post('/comment/artwork/{artworkId}', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{commentId}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::post('/report/artwork/{artworkId}', [ReportController::class, 'store'])->name('report.store');

    // Aksi Challenge (Submit)
    Route::get('/challenge/{challengeId}/submit', [ChallengeSubmissionController::class, 'create'])->name('challenge.submit.create');
    Route::post('/challenge/{challengeId}/submit', [ChallengeSubmissionController::class, 'store'])->name('challenge.submit.store');
});

/*
|--------------------------------------------------------------------------
| Rute Admin (Wajib Login + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/approve-curator/{id}', [AdminUserController::class, 'approveCurator'])->name('users.approveCurator');

    // Moderation
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderation/dismiss/{reportId}', [ModerationController::class, 'dismiss'])->name('moderation.dismiss');
    Route::post('/moderation/takedown/{reportId}', [ModerationController::class, 'takeDown'])->name('moderation.takeDown');
});

/*
|--------------------------------------------------------------------------
| Rute Curator (Wajib Login + Role Curator)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'curator'])->prefix('curator')->name('curator.')->group(function () {
    // Challenge Management (CRUD)
    Route::resource('challenges', CuratorChallengeController::class);

    // Submission Management
    Route::get('/submissions/challenge/{challengeId}', [CuratorSubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/winner/{submissionId}', [CuratorSubmissionController::class, 'selectWinner'])->name('submissions.selectWinner');
});

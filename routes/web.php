<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestScrapping;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CosineSimilarityController;

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
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::controller(CosineSimilarityController::class)->group(function () {
    Route::get('Cosine', 'index')->name('cosine.index');
    Route::post('Cosine', 'calculateSimilarity')->name('cosine.calculateSimilarity');
    Route::post('Cosines', 'calcaculateText')->name('cosine.calcaulateText');
    Route::post('ReadContent', 'readContext')->name('cosine.read');
    Route::post('Selected_Sentence', 'selected_sentences')->name('cosine.selected_sentences');
    Route::post('Google_search', 'google_search')->name('cosine.google_search');
    Route::post('searchDB', 'searchDB')->name('cosine.searchDB');
    Route::post('Google_scrap', 'scrapping_link')->name('cosine.scrapping_link');
});

Route::resource('Home', HomeController::class);
Route::resource('Scraper', TestScrapping::class);

require __DIR__.'/auth.php';

<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportFileController;

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

Route::get('/process', function () {
    return view('process');
});

Route::post('/upload', [ImportFileController::class, 'upload'])->name('upload');



function executeQueueCommand() {
    $command = 'php ' . base_path('artisan') . ' queue:work --stop-when-empty > NUL 2>&1 &';
    exec($command);
}

Route::post('/worker', function (Carbon $carbon) {
	executeQueueCommand();


    return view('process')->with('message', 'Fila Teve inicio em ' . $carbon->now()->format('d/m/Y H:i:s'));
})->name('worker');

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', [TestController::class,'show'])->name('testPage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('admin', [AdminController::class,'show'])->name('adminPage');

    Route::get('admin/testCreator', function(){
        return view('admin/testCreator');
    })->name('admin/testCreator');
    Route::post('admin/testCreator', [TestController::class,'createTest'])->name('testCreator.store');

    Route::get('admin/questionCreator/{test_id}', function($test_id)
    {
        session(['test_id' => $test_id]);
        return view('admin/addTestQuestion',[
            'test_id'=>$test_id,
        ]);
    });

    //Route::get('productDetail/{id}', function ($id) {
    //    return view('productDetail', ['productId' => $id]);
    //});


    Route::post('admin/questionCreator', [TestController::class,'addQuestion'])->name('createQuestion.store');

});

require __DIR__.'/auth.php';

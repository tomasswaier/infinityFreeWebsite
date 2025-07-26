<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('mainPage');

//example of old urls
// http://maryann.free.nf/test/#PPI_FINAL_EXAM_2024/2025/Alica(.MaryAnn)#1#30
//im thinking that if something like this comes I extract it with js and add link with

Route::get('subjects', [SubjectController::class,'showAllSubjects'])->name('subjects');

Route::get('subjects/{id}',[SubjectController::class,'showSubject']);
Route::get('test', [TestController::class,'show'])->name('testPage');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /*
     * here will go routes for study guides
     */
    Route::get('test/{yourFeelings}/{test_id}/{number_of_questions}', [TestController::class,'loadTest']);
    Route::get('test', [TestController::class,'show'])->name('testPage');
    Route::post('test', [TestController::class,'getTest'])->name('displayTest');

    Route::middleware('more_than_user')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('admin/testCreator', function(){
            return view('admin/testCreator');
        })->name('admin/testCreator');
        Route::post('admin/testCreator', [TestController::class,'createTest'])->name('testCreator.store');
        Route::get('admin/questionDisplay/{test_id}',[TestController::class,'showTestQuestionNames']);
        Route::get('admin/questionDelete/{questionId}',[TestController::class,'deleteQuestion']);

        Route::get('admin/questionCreator/{test_id}', function($test_id)
        {
            session(['test_id' => $test_id]);
            return view('admin/addTestQuestion',[
                'test_id'=>$test_id,
            ]);
        });
        Route::get('admin/questionEditor/{questionId}',[TestController::class,'editQuestion']);//edit question is used for getting data for editing the question


        Route::post('admin/questionCreator', [TestController::class,'addQuestion'])->name('question.store');
        Route::post('admin/questionEditor', [TestController::class,'updateQuestion'])->name('question.edit');

        Route::get('admin/subjectCreator',[SubjectController::class,'editSubject']);//edit question is used for getting data for editing the question
        Route::get('admin/subjectCreator/{subjectId}',[SubjectController::class,'editSubject']);//edit question is used for getting data for editing the question
        Route::post('admin/subjectCreator',[SubjectController::class,'saveSubject'])->name('subject.store');


        Route::get('admin/tagCreator', function(){
            return view('admin/tagCreator');}
        );
        Route::post('admin/tagCreator',[SubjectController::class,'saveTag'])->name('tag.store');

        Route::get('admin', [AdminController::class,'show'])->name('adminPage');
        Route::middleware('is_admin')->group(function () {


        });
    });
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use illuminate\support\facades\route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\AdminController;

Route::get('/', [SchoolController::class, 'showAll'])->name('mainPage');
//example of old urls
// http://maryann.free.nf/test/#PPI_FINAL_EXAM_2024/2025/Alica(.MaryAnn)#1#30
//im thinking that if something like this comes I extract it with js and add link with

Route::get('subjects', function(){
    return redirect('/');
});
Route::get('subjects/{id}', [SubjectController::class,'showAllSubjects']);
Route::get('subjects/info/{id}',[SubjectController::class,'showSubject']);

Route::get('school/',function(){
    return redirect('/');
});
Route::get('school/{id}',function($id){
    return view('school',['school_id'=>$id]);
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /*
     * here will go routes for study guides
     */
    Route::get('test/{yourFeelings}/{test_id}/{number_of_questions}', [TestController::class,'loadTest']);
    Route::get('test/{id}', [TestController::class,'show']);
    Route::post('test', [TestController::class,'getTest'])->name('displayTest');
    Route::get('test', function(){
        return redirect('/');
    });
    //Route::post('test', [TestController::class,'getTest'])->name('displayTest');
    Route::get('/profile', [ProfileController::class, 'info'])->name('profile.info');
    Route::get('/profile/{id}', [ProfileController::class, 'info']);
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/update/{id}', [ProfileController::class, 'update']);

    Route::middleware('more_than_user')->group(function () {

        Route::get('admin/testCreator/{id}', function($id){
            return view('admin/testCreator',[
                'school_id'=>$id,
            ]);
        });
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

        Route::get('admin/subjectCreator/{school_id}',[SubjectController::class,'editSubject']);
        Route::get('admin/subjectCreator/{school_id}/{subject_id}',[SubjectController::class,'editSubject']);
        Route::post('admin/subjectCreator',[SubjectController::class,'saveSubject'])->name('subject.store');


        Route::get('admin/tagCreator/{id}', function($id){
            return view('admin/tagCreator',[
                'school_id'=>$id
            ]);
        });
        Route::post('admin/tagCreator',[SubjectController::class,'saveTag'])->name('tag.store');

        Route::get('admin', [AdminController::class,'show'])->name('adminPage');
        Route::middleware('is_admin')->group(function () {
            Route::get('admin/schoolCreator', function(){
                return view('admin/schoolCreator');}
            );
            Route::post('admin/schoolCreator',[SchoolController::class,'save'])->name('school.store');

            Route::get('admin/users/manage', [ProfileController::class,'showAll']);



        });
    });
});

require __DIR__.'/auth.php';

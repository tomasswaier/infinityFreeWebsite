<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\McpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('mcp_key')->prefix('mcp')->group(function () {
    Route::get('/tests', [McpController::class,'listTests']);
    Route::get('/tests/{test_id}/questions', [McpController::class,'listQuestions']);
    Route::post('/questions', [McpController::class,'addQuestion']);
});

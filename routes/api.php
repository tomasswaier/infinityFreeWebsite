<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\McpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/mcp/add/writeIn', [McpController::class,'addWriteInQuestion']);
Route::post('/mcp/add/booleanChoiceAdvanced', [McpController::class,'addBooleanChoiceQuestionAdvanced']);
Route::post('/mcp/add/booleanChoiceSimple', [McpController::class,'addBooleanChoiceQuestionSimple']);
Route::post('/mcp/add/booleanChoiceOneCorrectSimple', [McpController::class,'addBooleanChoiceQuestionOneCorrectSimple']);
Route::post('/mcp/add/openAnswer', [McpController::class,'addOpenAnswerQuestion']);

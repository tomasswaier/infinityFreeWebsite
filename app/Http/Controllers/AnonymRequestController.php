<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

use App\Models\AnonymRequest;

class AnonymRequestController extends Controller
{
    public function store(Request $request){
        //TODO move these bullshits to validator
        if (!$request["source"]) {
            return response()->json([
                'success' => false,
                'message' => 'missing parameter source'
            ]);
        }
        if (!$request["text"]) {
            return response()->json([
                'success' => false,
                'message' => 'missing parameter text'
            ]);
        }
        Log::info($request["text"].$request["source"]);
        AnonymRequest::create([
            'source'=>$request["source"],
            'text'=>$request["text"]
        ]);


            return response()->json([
                'success' => true,
            ]);
    }
    public function delete(Request $request,$request_id){
        AnonymRequest::find($request_id)->delete();
        return redirect('admin/');
    }
}

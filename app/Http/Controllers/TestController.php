<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Test;
use Illuminate\Support\Facades\Redirect;

class TestController extends Controller
{
    public function show()
    {
        return view('test/index',['tests'=>Test::all()]);
    }
    //
    public function saveTest(Request $request) {
        try {
            $id=Test::create([
                'test_name'=>$request['test_name'],
                'test_author'=>Auth::user()->name,
                'number_of_submits'=>0,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Test creation failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

        }
        return redirect('admin')->with('success', 'owo created!');

    }
}

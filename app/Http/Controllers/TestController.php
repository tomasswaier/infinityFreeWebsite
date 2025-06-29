<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Test;

class TestController extends Controller
{
    public function show()
    {
        return view('test/index',['tests'=>Test::all()]);
    }
    //
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Test;

class AdminController extends Controller
{
    //
    public function show()
    {
        return view('admin/index',['tests'=>Test::all()]);
    }

}

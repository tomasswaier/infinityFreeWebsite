<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\AnonymRequest;

class AdminController extends Controller
{
    //
    public function show()
    {
        return view('admin/index',['tests'=>Test::all(),'anonymRequests'=>AnonymRequest::all()]);
    }

}

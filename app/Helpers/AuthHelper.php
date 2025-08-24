<?php
//namespace App\Helpers;

use App\Models\SchoolSupervisor;
use Illuminate\Support\Facades\Log;

function supervisesSchool($user,$school_id){
    if ($user->authorization=='admin') {
        return true;
    }
    if (!$school_id||!$user||$user->authorization!='supervisor') {
        return false;
    }
    $allSupervised=SchoolSupervisor::query()->where('user_id','=',$user->id)->get();
    foreach($allSupervised as $supervisedSchool){
        if ($supervisedSchool['school_id']==$school_id) {
            return true;
        }
    }
    return false;
}

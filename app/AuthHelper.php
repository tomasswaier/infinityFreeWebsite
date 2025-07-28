<?php

namespace App;

use App\Models\SubjectSupervisor;

class AuthHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

}
function supervisesClass($user,$school_id){
    if (!$school_id||!$user||$user->authorization!='supervisor') {
        return false;
    }
    if ($user->authorization=='admin') {
        return true;
    }
    $allSupervised=SubjectSupervisor::query()->where('user_id','=',$user->id);
    foreach($allSupervised as $supervisedSchool){
        if ($supervisedSchool->school_id==$school_id) {
            return true;
        }
    }
    return false;
}

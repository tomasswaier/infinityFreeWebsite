?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSupervisor extends Model
{
    protected $fillable=[
        'user_id',
        'school_id'
    ];
}

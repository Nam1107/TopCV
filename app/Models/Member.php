<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    // id int
    // members_id int
    // company_id int
    // status string
    // start_date datetime
    // end_date datetime

    protected $fillable = [
        'member_id',
        'company_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'members';
}

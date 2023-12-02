<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    // name ,
    // salary string
    // sex string
    // desc string
    // exp string
    // quantity int
    // level string
    // type string
    // company_id int
    // created_by int
    // expire_at datetime
    // status string

    // protected $guarded = [];
    protected $fillable = [
        'name', 
        'salary', 
        'sex', 
        'desc', 
        'exp',
        'quantity' ,
        'level' ,
        'type' ,
        'company_id' ,
        'created_by' ,
        'expire_at' ,
        'status' ,
    ];
    protected $primaryKey = 'id';
    protected $table = 'jobs';
}

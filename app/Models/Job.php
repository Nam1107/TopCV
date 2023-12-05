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
        "title", 
        "salary", 
        "sex_required", 
        "desc", 
        "exp_required",
        "quantity" ,
        "level_required" ,
        "field_of_job" ,
        "company_id" ,
        "created_by" ,
        "expire_at" ,
    ];
    protected $primaryKey = 'id';
    protected $table = 'jobs';

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
}

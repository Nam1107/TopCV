<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_follow_list extends Model
{
    use HasFactory;
    // id int
    // user_id int
    // company_id int
    protected $fillable = [
        "user_id",
        "company_id",
    ];
    protected $primaryKey = ['user_id', 'company_id'];
    public $incrementing = false;
    protected $table = 'Company_follow_list';

    public function userFollow()
    {
        return $this->belongsTo(User::class,'id','user_id');
    }
    public function companyFollow()
    {
        return $this->belongsTo(Company::class,'id','company_id');
    }
}

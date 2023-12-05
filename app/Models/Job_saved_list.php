<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_saved_list extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'job_id',
    ];
    protected $primaryKey = ['user_id', 'job_id'];
    public $incrementing = false;
    protected $table = 'Job_saved_list';
}

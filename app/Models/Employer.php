<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;
    // id int
    // employer_id int
    // company_id int
    // status string
    // start_date datetime
    // end_date datetime

    protected $fillable = [
        'employer_id',
        'company_id',
        'status',
        'start_date',
        'end_date',
    ];
    protected $primaryKey = 'id';
    protected $table = 'Employer';
}

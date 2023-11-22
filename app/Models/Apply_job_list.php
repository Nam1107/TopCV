<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply_job_list extends Model
{
    use HasFactory;
//     id int
//   user_id int
//   job_id int
//   company_id int
//   status string
//   user_cv string
  protected $fillable = [
    "user_id",
    "job_id",
    "company_id",
    "status",
    "user_cv",
];
protected $primaryKey = 'id';
protected $table = 'Apply_job_list';
}

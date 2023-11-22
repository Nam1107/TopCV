<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_cv extends Model
{
    use HasFactory;
//     id int
//   user_id int
//   url string
    protected $fillable = [
        "id",
        "user_id",
        "url"
    ];
    protected $primaryKey = 'id';
    protected $table = 'User_cv';
    
}

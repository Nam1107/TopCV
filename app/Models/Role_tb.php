<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_tb extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'role_name',
    ];
    protected $primaryKey = 'id';
    protected $table = 'role_tb';
}

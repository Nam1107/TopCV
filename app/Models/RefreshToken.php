<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fill_table = [
        'user_id',
        'refresh_token',
    ];
    protected $primaryKey = 'id';
    protected $table = 'refresh_tokens';
}

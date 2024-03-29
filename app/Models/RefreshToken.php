<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = [
        'user_id',
        'refresh_token',
        'token_type',
    ];
    protected $primaryKey = 'id';
    protected $table = 'refresh_tokens';
}

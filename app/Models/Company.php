<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'name',
        'email',
        'address',
        'district',
        'city',
        'phone',
        'logo',
        'detail',
        'url_page',
        'owner_id',
        'follow_count',
    ];
    protected $primaryKey = 'id';
    protected $table = 'company';

    public function ownedBy()
    {
        return $this->belongsTo(User::class,'owner_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fill_table = [
        'name',
        'email',
        'address',
        'district',
        'city',
        'phone',
        'logo',
        'details',
        'url',
        'owner_id',
    ];
    protected $primaryKey = 'id';
    protected $table = 'company';

    public function ownedBy()
    {
        return $this->belongsTo(Customer::class);
    }
}
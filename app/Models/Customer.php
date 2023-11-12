<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fill_table = [
        'name',
        'type',
        'email',
        'address',
        'city', 
        'state', 
        'postal_code'
    ];
    protected $primaryKey = 'id';
    protected $table = 'customers';

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }


}
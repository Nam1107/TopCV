<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fill_table = [
        'name_company',
        'phone_company',
        'address_company',
        'email_company',
        'city_company'
    ];
    protected $primaryKey = 'id';
    protected $table = 'company';
}
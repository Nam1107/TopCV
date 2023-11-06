<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fill_table = [
        'customer_id', 
        'amount', 
        'status', 
        'billed_date', 
        'paid_date',
    ];
    protected $primaryKey = 'id';
    protected $table = 'invoices';

    // 'customer_id' => Customer::class,
    //         'amount' => $this->faker->numberBetween(100, 20000),
    //         'status' => $status,
    //         'billed_date' => $this->faker->dateTimeThisDecade(),
    //         'paid_date' => $status == 'P' ? $this->faker->dateTimeThisDecade() : NULL,
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilters;
class CustomerFilter extends ApiFilters
{
    protected $safeParms = [
        'name' => ['eq','lk'],
        'type' => ['eq','ne'],
        'email' => ['eq','lk'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq','gt','lt'],
    ];
    protected $columnMap =[
        'postalCode'=>'postal_code'
    ];
    protected $operatorMap = [
        'eq'=> '=',
        'lt'=>'<',
        'lte'=>'<=',
        'gt'=>'>',
        'gte'=>'>=',
        'ne'=>'!=',
    ];

    
}
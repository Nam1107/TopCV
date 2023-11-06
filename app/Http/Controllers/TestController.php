<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //\
    public function index()
    {
        echo 'Index function';
        die;
    }
    public function getCategory($id = null)
    {
        echo 'new function = ' . $id;
        die;
    }
}
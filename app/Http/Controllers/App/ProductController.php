<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('app.products.index');
    }
}

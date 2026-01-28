<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index()
    {
        return view('app.clients.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        // echo xdebug_info();
        return view('quotes.index', ['quotes' => Quote::all()]);
    }
}

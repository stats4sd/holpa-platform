<?php

namespace App\Http\Controllers;

use App\Models\TempResult;
use Illuminate\Http\Request;

class TempResultsController extends Controller
{
    // index
    public function index()
    {
        return TempResult::with('country')->get();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tahun;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun = Tahun::latest()->paginate(10);
        return view('tahun_ajaran.index', compact('tahun'));
    }

    public function create()
    {
        return view('tahun_ajaran.create');
    }
}

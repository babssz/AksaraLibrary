<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use app\Models\User;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return view('admin.users.dashboard');
        } elseif ($user->isMahasiswa()) {
            return view('mahasiswa.dashboard');
        } else {
            return view('pegawai.dashboard');
        }
    }

    public function admin(): View
    {
        return view('admin.users.dashboard');
    }

    public function mahasiswa(): View
    {
        return view('mahasiswa.dashboard');
    }

    public function pegawai(): View
    {
        return view('pegawai.dashboard');
    }
}
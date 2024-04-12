<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DatabaseConnectionController extends Controller
{
    public function check()
    {
        try {
            DB::connection()->getPdo();
            return 'Koneksi database berhasil!';
        } catch (\Exception $e) {
            return 'Koneksi database gagal: ' . $e->getMessage();
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        return DB::select(
            'SELECT id,ip,route,created_at FROM logs ORDER BY id asc'
        );
    }
    public function show($id)
    {
        return DB::select(
            'select * from logs where id=?',
            [$id]
        );
    }
    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO logs (ip,route) VALUES (?,?)',
            [$request->ip, $request->route]
        );
    }
    public function update(Request $request, $id)
    {
        $log = Log::find($id);

        $log->ip = $request->ip;
        $log->route = $request->route;

        $log->save();
    }

    public function destroy($id)
    {
        Log::find($id)->delete();
    }
}

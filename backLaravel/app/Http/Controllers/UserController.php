<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        return DB::select(
            'SELECT id,email,username,phone FROM users WHERE deleted_at IS NULL ORDER BY id asc'
        );
    }
    public function show($id)
    {
        return DB::select(
            'select * from users where id=? and deleted_at is null',
            [$id]
        );
    }
    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO users (username,password,phone,email) VALUES (?,?,?,?)',
            [$request->username, Hash::make($request->password), $request->phone, $request->email]
        );
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->save();
    }

    public function destroy($id)
    {
        User::find($id)->delete();
    }
}

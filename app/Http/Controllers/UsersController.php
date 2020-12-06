<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all()->where('id', '<>', 1);
        return view('backend.pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('backend.pages.users.addUser');
    }

    public function store(Request $request)
    {
        try {
            if(strlen($request->password) < 8){
                return redirect('admin/users/create')->with('error', 'Password length must be greater than or equal 8 characters.');
            }

            $dateNow = date('Y-m-d H:i:s');
            $hashedPassword = Hash::make($request->password);

            DB::insert('insert into users (name, email, password, activate, created_at, updated_at, last_login) values (?,?,?,?,?,?,?) ',
                [$request->username, $request->email, $hashedPassword, true, $dateNow, $dateNow, $dateNow]);

            return redirect('admin/users/create')->with('success','User created successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/users/create')->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::find($id);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('backend.pages.users.editUser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            if($request->password == null) {
                DB::update('update users set name = ?, email = ?, updated_at = ? where id = '. $id,
                    [$request->name, $request->email, $dateNow]);
            } else {
                if(strlen($request->password) < 8){
                    return redirect('admin/users/' . $id . '/edit')->with('error', 'Password length must be greater than or equal 8 characters.');
                }

                $hashedPassword = Hash::make($request->password);
                DB::update('update users set name = ?, email = ?, password = ?, updated_at = ? where id = '. $id,
                    [$request->username, $request->email, $hashedPassword, $dateNow]);
            }

            return redirect('admin/users/' . $id . '/edit')->with('success','User updated successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/users/' . $id . '/edit')->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect('/admin/users');
    }

    public static function updateUserActivate(Request $request, $id)
    {
        try {
            DB::update('update users set activate = ? where id = ?', [$request->activate, $id]);

            return response()->json(['message' => "User is Updated Successfully!"], 201);
        }catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }
}

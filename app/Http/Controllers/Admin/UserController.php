<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Gate;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        echo json_encode($users);
    }

    public function edit(User $user)
    {
        if(Gate::denies('owner')){
            return response()->json([
                'status'=>false,
                'message'=>'Only owner can get action'
                ]);
        }
        $roles = Role::all();

        $params = [
            'roles' => $roles,
            'user' => $user,
        ];

        echo json_encode($params);
    }

    public function update(Request $request, User $user)
    {
        $user->roles()->sync($request->roles);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        if(Gate::denies('owner')){
            return response()->json([
                'status'=>false,
                'message'=>'Only owner and Managers can get action'
                ]);
        }
        $user->roles()->detach();
        $user->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Employee account deleted'
            ]);
    }
}

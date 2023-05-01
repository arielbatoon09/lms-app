<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    // Get User Data
    public function index()
    {
        try{
            return Inertia::render('Admin/User-management', [
                'users' => User::all()->map(function ($user){
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'profile_img' => $user->profile_img,
                        'is_admin' => $user->is_admin,
                        'created_at' => \Carbon\Carbon::parse($user->created_at)->format('d/m/Y - H:i:s'),
                        'updated_at' => \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y - H:i:s'),
                    ];
                })
            ]);
        }catch(\Exception $error){
            return $error->getMessage();
        }
    }
    // Update User Data Role
    public function update(Request $request, $id)
    {
        try{
            Validator::make($request->all(), [
                'is_admin' => ['required'],
            ])->validate();

            $user = User::find($id);

            if($user){
                $user->update($request->all());

                return redirect()->back()
                    ->with('message', 'Updated user role.');
            }

        }catch(\Exception $error){
            return $error->getMessage();
        }
    }
    // Delete User
    public function destroy($id)
    {
        $user = User::find($id);

        if($user){
            $user->delete();
            return redirect()->back()
                ->with('message', 'User deleted successfully.');
        }
    }
}

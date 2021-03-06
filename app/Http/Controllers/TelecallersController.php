<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TelecallersController extends Controller
{
    public function index()
    {
        $telecallers = User::where('role', 'Telecaller')->get();
        return view('common.telecallers')->with('telecallers', $telecallers);
    }

    public function store(Request $request, $id = null) 
    {
        if ($id && $request->user()->role === "Admin")
        {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'email|required|string|max:255|unique:users,id,'.auth()->user()->id,
                'password' => 'nullable|string|confirmed',
                'phone' => 'required|string|size:10|unique:users,id,'.auth()->user()->id
            ]);

            $user = User::find($id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if($request->input('password'))
                $user->password = Hash::make($request->input('password'));
            $user->phone = $request->input('phone');
    
            $user->save();

            return redirect('/telecallers');
        }
        elseif ($id === null) {

            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'email|required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|size:10'
            ]);

            $user = new User;
            
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->phone = $request->input('phone');
            $user->role = 'Telecaller';

            $user->save();

            return redirect('/telecallers');
        }

        return redirect('/');
    }

    public function delete($id)
    {
        User::destroy($id);
        return redirect('/telecallers');
    }
    
    public function edit($id)
    {
        $telecaller = User::find($id);
        return view('common.edit_telecaller')->with('telecaller', $telecaller);
    }
}
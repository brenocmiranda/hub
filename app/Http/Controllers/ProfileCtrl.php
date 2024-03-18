<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProfileRqt;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UsersLogs;

class ProfileCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function show()
    {
        //
    }

    public function edit()
    {      
        return view('system.profile');
    }

    public function update(ProfileRqt $request)
    {    
        
        Users::find(Auth::user()->id)->update([
            'name' => $request->name, 
            'email' => $request->email,
        ]);

        // Handle File Upload
        if($request->hasFile('src')){
            // Get filename with the extension
            $filenameWithExt = $request->file('src')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('src')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('src')->storeAs('/profile', $fileNameToStore, 'public');

            Users::find(Auth::user()->id)->update([
                'src' => $fileNameToStore,
            ]);
        }

        if($request->password){
            Users::find(Auth::user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização do perfil',
            'description' => 'Foi realizado a atualização das suas informações de perfil.',
            'action' => 'update',
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('profile.edit')->with('edit', true);
    }
}

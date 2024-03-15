<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProfileRqt;
use Illuminate\Http\Request;
use App\Models\Users;

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
                'name' => $request->name, 
                'email' => $request->email,
                'src' => $fileNameToStore ? $fileNameToStore : null,
                'password' => Hash::make($request->password)
            ]);
        }else {
            Users::find(Auth::user()->id)->update([
                'name' => $request->name, 
                'email' => $request->email,
                'src' => $fileNameToStore ? $fileNameToStore : null
            ]);
        }

        return redirect()->route('profile.edit')->with('edit', true);
    }
}

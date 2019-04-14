<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;

class UserController extends Controller
{
    //
    public function profile(){
    	return view ('account',array ('user' =>Auth::user()));
    }

    public function update_avatar(Request $request, $id){
    	   // Handle the user upload of avatar
    	   if($request->hasFile('avatar'))
           {
    		  $avatar = $request->file('avatar');
    		  $filename = time() . '.' . $avatar->getClientOriginalExtension();
    		  Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );

    		  $user = Auth::user();
    		  $user->avatar = $filename;
    		  $user->save();
    	   }
           return view('account', array('user' => Auth::user()) );

    }


    public function update_personal_info(Request $request, $id)
    {
            $location = "";
            $birthday = "";
            $gender = "";

            if(!empty($request->gender))
            {
                $gender = $request->gender;
            }

            if (!empty($request->birthday))
            {
                $birthday = $request->birthday;
            }

            if (!empty($request->location))
            {
                $location = $request->location;
            }

            $user = Auth::user();
            $user->gender = $gender;
            $user->location = $location;
            $user->birthday = $birthday;
            $user->save();
            return view('account', array('user' => Auth::user()) );
    }


    public function update_about_me(Request $request, $id){
            $aboutMe = "";

            if (!empty($request->about_me))
            {
                $aboutMe = $request->about_me;
            }

            $user = Auth::user();
            $user->about_me = $aboutMe;
            $user->save();
            return view('account', array('user' => Auth::user()) );
        }

    public function getAboutMe()
    {
        return $user->about_me;
    }

}

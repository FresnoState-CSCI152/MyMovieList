<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use Illuminate\Support\Facades\Hash;

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
            $gender = "";
            $b_month;
            $b_day;
            $b_year;

            if(!empty($request->gender))
            {
                $gender = $request->gender;
            }

            if (!empty($request->location))
            {
                $location = $request->location;
            }

            $user = Auth::user();
            $user->gender = $gender;
            $user->location = $location;
            $user->birth_day = $request->b_day;
            $user->birth_month = $request->b_month;
            $user->birth_year = $request->b_year;
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

    public function getAboutMe(Request $request){
        $user = Auth::user();
        return $user->about_me;
    }

    public function getUserInfo(Request $request){
        $user = Auth::user();

        $userInfo = array("month"=>$user->birth_month, "day"=>$user->birth_day, "year"=>$user->birth_year, "local"=>$user->location);

        return $userInfo;
    }

    public function showForm()
    {
        $user = Auth::user();
        
        return view('resetUserPassword', array('user' => Auth::user()));
    }

    public function updatePassword(Request $request)
    {
        $new_password = $request->new_password;

        $user = Auth::user();
        $new_n = Hash::make($new_password);
        $user->password = $new_n;
        $user->save();
        return $this->profile();
    }
}

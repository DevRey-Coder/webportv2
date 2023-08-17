<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
            "role" => "in:admin,staff",
            "phone_number" => "nullable|string",
            "gender" => "required|in:male,female",
            "date_of_birth" => "required|date",
            "address" => "required|string"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role,
            "phone_number" => $request->phone_number,
            "gender" => $request->gender,
            "date_of_birth" => $request->date_of_birth,
            "address" => $request->address,
        ]);

        if($request->hasFile('user_photo')){
            $file = $request->file('photo');
            $fileName = time().".".$file->getClientOriginalExtension();

            $file->storeAs('public/photos/',$fileName);
            $user = Auth::user();

            $media = Media::create([
                'filename' => $user->name."_profile",
                'url' => Storage::url('public/photos/', $fileName)
            ]);

            $photo = Photo::create([
                'name' => $user->name."_profile",
                'url' => $media->url,
                'ext' => $file->getClientOriginalExtension(),
                'user_id' => $request->user_id
            ]);

            $media->photo()->save($photo);

            $user->update(['user_photo'=>$photo->url]);
        }

        return response()->json([
            "message" => "User register successful",
        ]);

    }
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]);

        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                "message" => "Username or password wrong",
            ]);
        }

        return Auth::user()->createToken('admin-token',['admin']);

    }

    public function changePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();

        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'message' => 'Current password is incorrect'
            ],400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successful"
        ]);
    }

    public function logoutAll(){
        foreach (Auth::user()->tokens as $token) {
            $token->delete();
        }
        return response()->json([
            "message" => "logout all devices successful"
        ]);
    }

    // public function devices(){
    //     return Auth::user()->tokens;
    // }
}


//TOtal Stock ထည့်ရန်
// Voucher ထုတ်တိုင်း Record Create လုပ်ရန်
// Stock Update လုပ်တိုင်း Product Stock အပြောင်းအလဲဖြစ်ရမယ်
// Vouncher ထွက်ပြီးတိုင်း Product Stock ပြန်နှုတ်ရန်

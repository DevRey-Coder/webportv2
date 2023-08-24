<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class ApiAuthController extends Controller
{
    public function register(Request $request, User $user)
    {
        if (Auth::user()->role == "staff") {
            return response()->json([
                "message" => "You can't register as a staff",
            ]);
        }
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "address" => "required | max:30",
            "gender" => "required",
            "date_of_birth" => "required",
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "address" => $request->address,
            "gender" => $request->gender,
            "role" => $request->role,
            "phone_number" => $request->phone_number,
            "date_of_birth" => $request->date_of_birth,
        ]);

        return response()->json([
            "message" => "User register successful",
        ]);

    }
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                "message" => "Username or password wrong",
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('admin-token',['admin']);

        return response()->json([
            'name' => $user->name,
            'token' => explode("|", $token->plainTextToken)[1],
            'id' => $user->id,
        ]);
        
        if (Auth::user()->ban == true) {
            return response()->json([
                "message" => "Your account is banned.",
            ]);
        }
        return Auth::user()->createToken('admin-token', ['admin']);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successful",
        ]);
    }

    public function logoutAll()
    {
        foreach (Auth::user()->tokens as $token) {
            $token->delete();
        }
        return response()->json([
            "message" => "logout all devices successful",
        ]);
    }

    // public function devices(){
    //     return Auth::user()->tokens;
    // }
    public function showCurrentUser()
    {
        $user = Auth::user();
        // return UserResource::collection($user);
        // return response()->json($user);
        return new UserResource($user);
        
    }
    public function showAllUser()
    {
        $query = request()->input('query');

        $users = User::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('name', 'like', "%$query%");
            // ->orWhere('brand', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();
        return UserResource::collection($users);
    }

    public function ban(string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->ban = true;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'User has been banned.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }
    }
    public function unban(string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->ban = false;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User has been unbanned.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }
    }
}

//TOtal Stock ထည့်ရန်
// Voucher ထုတ်တိုင်း Record Create လုပ်ရန်
// Stock Update လုပ်တိုင်း Product Stock အပြောင်းအလဲဖြစ်ရမယ်
// Vouncher ထွက်ပြီးတိုင်း Product Stock ပြန်နှုတ်ရန်

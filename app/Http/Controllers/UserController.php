<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
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

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

   
    public function update(Request $request, string $id)
    {
        //
    }

   
    public function destroy(string $id)
    {
        //
    }
}

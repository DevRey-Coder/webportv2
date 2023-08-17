<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Models\Photo;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {
        // $file = $request->file('photo');
        // $fileName = time().".".$file->getClientOriginalExtension();

        // $file->storeAs('public/photos/',$fileName);
        // $user = Auth::user();

        // $media = Media::create([
        //     'filename' => $user->name."_profile",
        //     'url' => Storage::url('public/photos/', $fileName)
        // ]);

        // $photo = Photo::create([
        //     'name' => $user->name."_profile",
        //     'url' => $media->url,
        //     'ext' => $file->getClientOriginalExtension(),
        //     'user_id' => $request->user_id
        // ]);

        // $media->photo()->save($photo);

        // return response()->json([
        //     'message'=> 'profile is added'
        // ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        // Validate the incoming request
        $request->validate([ 'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', ]);

        // Delete the existing photo and associated media
        $media = $photo->media;
        $photo->delete(); Storage::delete($media->filename);

        // Upload the new photo
        $file = $request->file('photo');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/photos/', $fileName);

        // Create a new media record
         $media = Media::create([ 'filename' => $fileName, 'url' => Storage::url('public/photos/' . $fileName), ]);

         // Create a new photo record and associate it with the media
         $user = Auth::user();
         $photo = Photo::create([ 'name' => $user->name . "_profile", 'url' => $media->url, 'ext' => $file->getClientOriginalExtension(), 'user_id' => $user->id, ]);
         $photo->media()->associate($media)->save();

         return response()->json([ 'message' => 'Photo updated successfully', ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        $media = $photo->media;
        $photo->delete();
        Storage::delete($media->filename);

        return response()->json([
            'message' => 'Photo deleted successfully'
        ]);
    }
}

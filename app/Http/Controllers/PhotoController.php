<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Resources\PhotoDetailResource;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::when(Auth::user()->role !== "admin", function ($query) {
            $query->where("user_id", Auth::id());
        })->latest("id")->get();

        if (empty($photos->toArray())) {
            return response()->json([
                "message" => "there is no photo",
            ]);
        }

        return PhotoResource::collection($photos);
    }

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
        if ($request->hasFile('photo')) {
            $photos = $request->file('photo');
            $savedPhotos = [];
            foreach ($photos as $photo) {
                $name = md5(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));
                $savedPhoto = $photo->store("public/photo");
                $size = $photo->getSize()/1024/1024;
                $savedPhotos[] = [
                    "url" => $savedPhoto,
                    "name" => $name,
                    "ext" => $photo->extension(),
                    "user_id" => Auth::id(),
                    "size" => $size,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            }
            Photo::insert($savedPhotos);
        }

        return response()->json([
            "message" => "uploaded photo successfully",
        ]);
    }

    public function show(Photo $photo)
    {
        $this->authorize('view', $photo);
        if (is_null($photo)) {
            return response()->json([
                "message" => "there is no photo",
            ]);
        }

        return new PhotoDetailResource($photo);
    }


    public function update(Request $request, string $id)
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
    public function destroy(string $id)
    {
        $photo = Photo::find($id);
        $this->authorize('delete', $photo);
        if (is_null($photo)) {
            return response()->json([
                "message" => "there is no photo"
            ]);
        }
        $photo->delete();
        Storage::delete($photo->url);
        return response()->json([
            "message" => "photo deleted successfully"
        ]);
    }
}

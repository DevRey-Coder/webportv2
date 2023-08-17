<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Resources\PhotoDetailResource;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $savedPhotos = [];
            foreach ($photos as $photo) {
                $name = md5(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));
                $savedPhoto = $photo->store("public/photo");
                $savedPhotos[] = [
                    "url" => $savedPhoto,
                    "name" => $name,
                    "ext" => $photo->extension(),
                    "user_id" => Auth::id(),
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
        //
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

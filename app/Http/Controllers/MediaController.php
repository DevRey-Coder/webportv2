<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;

class MediaController extends Controller
{
    public function index(){
        $media = Media::all();

        return MediaResource::collection($media);
    }

    public function show(Media $media){
        return response()->json([
            'data' => $media
        ]);
    }
}

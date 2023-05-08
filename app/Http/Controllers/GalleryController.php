<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use http\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function getGallery(){
        $gallery = Gallery::all();

        $changed_gallery = [];

        foreach ($gallery as $gallery_item){
            $sorted_gallery = [
                'id' => $gallery_item['id'],
                'imgSrc' => $gallery_item['img_src'],
                'publishedAt' => $gallery_item['updated_at'] ?? $gallery_item['created_at']
            ];
            array_push($changed_gallery, $sorted_gallery);
        }

        return response()->json([
            'data' => [
                'gallery' => $changed_gallery
            ]
        ], 200);
    }

    public function addGallery(Request $request){
        $validator = Validator::make($request->all(), [
            'img' => 'required|image'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ]);
        }

        $image = Storage::disk('public')->put('images', $request->img);
        $path = env('APP_DOMEN') . $image;

        $gallery = new Gallery();
        $gallery->img_src = $path;
        $gallery->save();

        return response()->json([], 204);
    }
}

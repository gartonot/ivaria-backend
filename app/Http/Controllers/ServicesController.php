<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function getServices(Request $request){

        $services = [];

        if($request->query('count') !== null){
            $services = Services::take($request->query('count'))->get();
        }

        else{
            $services = Services::all();
        }

        $changed_services = [];
        foreach ($services as $service_item){
            $sorted_services = [
                'id' => $service_item['id'],
                'title' => $service_item['title'],
                'description' => $service_item['description'],
                'imgSrc' => $service_item['img_src'],
                'publishedAt' => $service_item['updated_at']??$service_item['created_at']
            ];

            array_push($changed_services, $sorted_services);
        }

        return response()->json([
            'data' => [
                'services' => $changed_services
            ]
        ], 200);
    }

    public function addServices(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'img' => 'required|image'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $image = Storage::disk('public')->put('images', $request->img);
        $path = env('APP_DOMEN') . Storage::url($image);

        $services = new Services();

        $services->title = $request->title;
        $services->description = $request->description;
        $services->img_src = $path;

        $services->save();

        return response()->json([], 204);

    }

    public function updateServices(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'img' => 'nullable|image'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        if(isset($request->img)){
            $image = Storage::disk('public')->put('images', $request->img);
            $path = env('APP_DOMEN') . Storage::url($image);
        }

        $services = Services::find($request->id);

        if($services === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        isset($request->title) ? $services->title = $request->title : false;
        isset($request->description) ? $services->description = $request->description : false;
        isset($path) ? $services->img_src = $path : false;

        $services->save();

        return response()->json([], 204);
    }

    public function deleteServices(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }
        $services = Services::find($request->id);

        if($services === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $services->delete();

        return response()->json([], 204);
    }

    public function getServicesByAmount(Request $request){
        $validator = Validator::make($request->all(), [
            'amount' => 'required|int'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $services = Services::take($request->amount)->get();

        $changed_services = [];
        foreach ($services as $service_item){
            $sorted_services = [
                'id' => $service_item['id'],
                'title' => $service_item['title'],
                'description' => $service_item['description'],
                'imgSrc' => $service_item['img_src'],
                'publishedAt' => $service_item['updated_at']??$service_item['created_at']
            ];

            array_push($changed_services, $sorted_services);
        }

        return response()->json([
            'data' => [
                'services' => $changed_services
            ]
        ], 200);
    }
}

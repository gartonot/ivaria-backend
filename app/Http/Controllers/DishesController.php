<?php

namespace App\Http\Controllers;

use App\Http\Resources\DishShortResource;
use App\Models\DishCategories;
use App\Models\Dishes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DishesController extends Controller
{
    private const MAX_DISHES_PER_PAGE = 15;
    public function getDishes(Request $request){
        $dishes = Dishes::query()->with('dish_categories');
        $dishes->when($request->input('category_id'), function ($query, $categoryId) {
            return $query->where('dish_categories_id', $categoryId);
        });

        return DishShortResource::collection(
            $dishes->paginate(min($request->input('count'), self::MAX_DISHES_PER_PAGE))
        );
    }

    public function getDishesByAmount(Request $request){
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

        $dishes = Dishes::take($request->amount)->get();

        $changed_dishes = [];
        foreach ($dishes as $dish_item){
            $category_dish_by_id = DishCategories::select('name')->find($dish_item['dish_categories_id']);
            $sorted_dishes = [
                'id' => $dish_item['id'],
                'title' => $dish_item['title'],
                'description' => $dish_item['description'],
                'price' => $dish_item['price'],
                'imgSrc' => $dish_item['img_src'],
                'category' => [
                    'id' => $dish_item['dish_categories_id'],
                    'name' => $category_dish_by_id['name'],
                ],
                'publishedAt' => $dish_item['updated_at'] ?? $dish_item['created_at'],
            ];
            array_push($changed_dishes, $sorted_dishes);
        }

        return response()->json([
            'data' => [
                'dishes' => $changed_dishes
            ]
        ], 200);
    }

    public function addDishes(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'int|required',
            'title' => 'string|required',
            'description' => 'string|required',
            'price' => 'string|required',
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

        $dish_categories = DishCategories::find($request->category_id);

        if(empty($dish_categories)){
            return response()->json([
                'error' => [
                    'message' => 'No categories exist with this id',
                    'isAdd' => false,
                    'code' => 422

                ]
            ], 422);
        }

        $image = Storage::disk('public')->put('images', $request->img);
        $path = env('APP_DOMEN') . Storage::url($image);

        $dish = new Dishes();
        $dish->title = $request->title;
        $dish->description = $request->description;
        $dish->price = $request->price;
        $dish->img_src = $path;

        $dish_categories->dishes()->save($dish);

        return response()->json([], 204);
    }

    public function updateDishes(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'price' => 'string|nullable',
            'img_src' => 'nullable|image'
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


        $dishes = Dishes::find($request->id);
        if($dishes === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }

        isset($request->title) ? $dishes->title = $request->title : false;
        isset($request->description) ? $dishes->description = $request->description : false;
        isset($request->price) ? $dishes->price = $request->price : false;
        isset($path) ? $dishes->img_src = $path : false;

        $dishes->save();

        return response()->json([], 204);
    }

    public function deleteDishes(Request $request){
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

        $dishes = Dishes::find($request->id);
        if($dishes === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $dishes->delete();

        return response()->json([], 204);

    }

    public function getCategories(){
        $categories = DishCategories::all();

        $changed_categories = [];
        foreach ($categories as $category_item){
            $sorted_categories = [
                'id' => $category_item['id'],
                'name' => $category_item['name']
            ];
            array_push($changed_categories, $sorted_categories);
        }

        return response()->json([
            'data' => [
                'categories' => $changed_categories
            ]
        ], 200);
    }

    public function addDishCategories(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
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

        $dish_categories = new DishCategories();

        $dish_categories->name = $request->name;

        $dish_categories->save();

        return response()->json([], 204);
    }

    public function updateDishCategories(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'name' => 'required|string|max:255'
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


        $dish_categories = DishCategories::find($request->id);

        if($dish_categories === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $dish_categories->name = $request->name;

        $dish_categories->save();

        return response()->json([], 204);
    }

    public function deleteDishCategories(Request $request){
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

        $categories = DishCategories::find($request->id);

        if($categories === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $dishes = $categories->dishes;

        if(empty($dishes)){
            return response()->json([
                'data' => [
                    'isDelete' => false
                ]
            ], 422);
        }

        $categories->delete();

        return response()->json([], 204);


    }

}

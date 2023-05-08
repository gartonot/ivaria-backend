<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    //TODO:Изменить update at и created at на published at
    public function getNews(Request $request){

        $news = [];
        $news_categories = [];

        if($request->query('category_id') !== null){

            $category = NewsCategories::find($request->query('category_id'));

            if($category === null){
                return response()->json([
                    'error' => [
                        'code' => 404,
                        'message' => 'Data with that id doesnt exist'
                    ]
                ], 404);
            }
            $news = $category->news;
        }

        else if($request->query('count') !== null){
            $news = News::take($request->query('count'))->get();
        }

        else{
            $news = News::all();
            $news_categories = NewsCategories::all();
        }

        $sorted_news = [];
        foreach ($news as $item){

            $category_news_by_id = NewsCategories::select('name')->find($item['categories_id']);
            $changed_news = [
                'id' => $item['id'],
                'title' => $item['title'],
                'description' => $item['description'],
                'publishedAt' => $item['updated_at'] ?? $item['created_at'],
                'category' => [
                    'id' => $item['categories_id'],
                    'name' => $category_news_by_id['name'],
                ]
            ];
            array_push($sorted_news, $changed_news);
        }

        $changed_categories = [];
        foreach ($news_categories as $category){
            $categories_data = [
                'id' => $category['id'],
                'name' => $category['name'],
            ];
            array_push($changed_categories, $categories_data);

        }

        return response()->json([
            'data' => [
                'news' => $sorted_news,
                'categories' => $changed_categories
            ]

        ], 200);
    }

    public function addNews(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|int',
            'title' => 'required|string',
            'description' => 'required|string'
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

        $news_categories = NewsCategories::find($request->category_id);

        if(empty($news_categories)){
            return response()->json([
                'error' => [
                    'message' => 'No categories exist with this id',
                    'isAdd' => false,
                    'code' => 422

                ]
            ], 422);
        }

        $news = new News();

        $news->title = $request->title;
        $news->description = $request->description;

        $news_categories->news()->save($news);

        return response()->json([], 204);
    }

    public function updateNews(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'title' => 'nullable|string',
            'description' => 'nullable|string'
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

        $news = News::find($request->id);

        if($news === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        isset($request->title) ? $news->title = $request->title : false;
        isset($request->description) ? $news->description = $request->description : false;

        $news->save();

        return response()->json([], 204);
    }

    public function deleteNews(Request $request){
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

        $news = News::find($request->id);

        if($news === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $news->delete();

        return response()->json([], 204);
    }

    public function getNewsCategories(){
        $categories = NewsCategories::all();

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

    public function addNewsCategories(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
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

        $categories = new NewsCategories();

        $categories->name = $request->name;

        $categories->save();

        return response()->json([], 204);

    }

    public function updateNewsCategories(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'name' => 'required|string'
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


        $categories = NewsCategories::find($request->id);

        if($categories === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $categories->name = $request->name;

        $categories->save();

        return response()->json([], 204);
    }

    public function deleteNewsCategories(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
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

        $categories = NewsCategories::find($request->id);

        if($categories === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }
        $news = $categories->news;

        if(empty($news)){
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_column' =>  'in:id,created_at,title',
            'order_direction' => 'in:desc,asc',
            'page' => 'integer',
            'count' => 'integer'
        ]);

        if ($validator->fails()) {
            return  response()->json([
                'errors' => $validator->messages(), 
                'success'=> 'false'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $params = $validator->validated();

        $orderColumn = array_key_exists('order_column', $params) ?
            $params['order_column'] : 'created_at';
       
        $orderDirection = array_key_exists('order_direction', $params) ?
            $params['order_direction'] : 'desc';
        
        $page =  array_key_exists('page', $params) ?
            $params['page'] : 1;

        $paginator = Post::with('category')
            ->when(request('category'), function($query) {
                $query->where('category_id', request('category'));
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(10);
        
        if ($page > $paginator->lastPage()) {
            return response()->json([
                'message' => 'Page not found', 
                'success'=> 'false'
            ], Response::HTTP_NOT_FOUND);
        }
        
        return PostResource::collection($paginator);
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());
        $post->addMedia($request->thumbnail->path())
            ->toMediaCollection('post');
        return new PostResource($post);
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $post->update($request->validated());

        return new PostResource($post);
    }
}

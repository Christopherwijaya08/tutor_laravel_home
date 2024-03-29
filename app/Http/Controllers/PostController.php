<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(){
        $posts = Post::with('writer:id,username')->get();
        // return response()->json(['data' => $posts]);
        return PostDetailResource::collection($posts);
    }

    public function show($id){
        $post = Post::with('writer:id,username')->findOrFail($id);
        // return response()->json(['data' => $post]);
        return new PostDetailResource($post);
    }

    public function showWithOutWriter($id){
        $post = Post::findOrFail($id);
        return new PostDetailResource($post);
    }

    public function store (Request $request){
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);

        $request['author'] = Auth::user()->id;
        $postDatabase = Post::create($request->all());
        return new PostDetailResource($postDatabase->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required',
        ]);        
    }
}

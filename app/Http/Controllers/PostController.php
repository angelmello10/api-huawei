<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function store(PostRequest $request)
    {
        $datos = $request->validated();
        $post = Post::create([
            ...$datos,
            'expira_en' => now()->addHours(2),
        ]);

        return response()->json(['data' => $post,'message' => 'Post creado correctamente']);
    }
    
}

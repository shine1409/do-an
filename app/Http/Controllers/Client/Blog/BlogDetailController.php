<?php

namespace App\Http\Controllers\Client\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Dish;

class BlogDetailController extends Controller
{
    //
    public function index(){
        return view('clients.blogs.blogDetail');
    }

    public function show($id)
    {
        $blogs = Post::findOrFail($id);
        $allDishes = Dish::orderBy('id','desc')->limit(4)->get();
        return view('clients.blogs.blogDetail',compact('blogs','allDishes'));
    }
}

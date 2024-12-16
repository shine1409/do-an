<?php

namespace App\Http\Controllers\Client\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Dish;


class BlogController extends Controller
{
    //
    public function index()
    {   
        $blogs = Post::paginate(4);
        $allDishes = Dish::orderBy('id','desc')->limit(4)->get();
        return view('clients.blogs.index',compact('blogs','allDishes'));
    }

    public function components()
    {
        $blogs = Post::paginate(5);
        
        return view('components.client.blog',compact('blogs'));
    }

    
}

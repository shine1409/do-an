<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        // $categories = Category::with(['dishes' => function ($query) {
        //     $query->take(6);
        // }])->take(6)->get();

        // $allDishes = Dish::take(8)->get();
        // $blogs = Post::paginate(10);

        // return view('clients.home.index', compact('categories', 'allDishes', 'blogs'));

        if(auth()->check()) {
            return redirect()->route('statistical.index');
        }else {
            return redirect()->route('login');
        }

    }
}

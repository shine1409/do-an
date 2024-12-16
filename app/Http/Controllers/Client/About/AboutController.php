<?php

namespace App\Http\Controllers\Client\About;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class AboutController extends Controller
{
    public function index()
    {
        $blogs = Post::paginate(10);
        return view('clients.about.index',compact('blogs'));
    }
}
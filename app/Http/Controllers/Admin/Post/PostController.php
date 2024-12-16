<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $post;
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = $this->post->orderBy('id', 'desc')->paginate(5);
        return view('admin.posts.list', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {

        $dataCreate = $request->validated();
        $dataCreate['user_id'] = Auth::id();
        Post::createPost($dataCreate);
        flash()->success('Tạo bài viết thành công.');
        return redirect()->route('post.list');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = $this->post->findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = $this->post->findOrFail($id);
        $dataUpdate = $request->validated();
        $this->post->updatePost($dataUpdate);
        flash()->success('Cập nhật bài viết thành công.');
        return redirect()->route('post.list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = $this->post->findOrFail($id);
        $post->delete();
        flash()->success('Xóa bài viết thành công.');
        return redirect()->route('post.list');
    }
}

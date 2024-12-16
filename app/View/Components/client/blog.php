<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;

class Blog extends Component
{
    public $blogs;

    public function __construct($blogs)
    {
        $this->blogs = $blogs;
    }

    public function render()
    {
        return view('components.client.blog');
    }
}

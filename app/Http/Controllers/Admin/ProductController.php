<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');
    $price = $request->input('price');
    $productes = Product::query()
        ->when($search, function($query) use ($search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhereHas('category', function($query) use ($search) {
                             $query->where('name', 'LIKE', "%{$search}%");
                         });
        })
        ->when($category, function ($query) use ($category) {
            return $query->where('category_id', $category);
        })
        ->when($price, function ($query) use ($price) {
            $priceRange = explode('-', $price);
            if (count($priceRange) == 2) {
                return $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
            } elseif ($price == '500000') {
                return $query->where('price', '>=', 500000);
            }
        })
        ->paginate(5)
        ->appends([
                'search' => $search,
                'category' => $category,
                'price' => $price,
            ]);
        $categories = Category::all();
    return view('admin.product.list', compact('productes','categories'));
}

    public function add()
    {
        $categories = Category::getAllCategories();
        return view('admin.product.add', compact('categories'));
    }

    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = Category::getAllCategories();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function store(CreateProductRequest $request)
    {
        Product::createNewProduct($request->validated());
        flash()->success('Thêm thành công.');
        return redirect()->route('product.list');
    }

    public function update(UpdateProductRequest $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->updateProduct($request->validated());
        flash()->success('Cập nhật thành công.');
        return redirect()->route('product.list');
    }

    public function delete($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->delete();
        flash()->success('Xóa thành công.');
        return redirect()->route('product.list');
    }
}

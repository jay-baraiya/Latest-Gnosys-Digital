<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $categories = Category::query()->where('status', 1)->get();

        $blogs = Blog::query()->where('status', 1)->paginate(9);

        return view('blogs.listing', compact('blogs', 'categories'));
    }

    public function show($slug)
    {
        $blog = Blog::with('category')->where('slug', $slug)->where('status', 1)->firstOrFail();

        $relatedBlogs = Blog::with('category')
            ->where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 1)
            ->latest('datetime')
            ->take(4)
            ->get();

        return view('blogs.details', compact('blog', 'relatedBlogs'));
    }
}

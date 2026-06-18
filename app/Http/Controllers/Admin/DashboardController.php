<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\DigitalProduct;
use App\Models\DigitalService;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user_count = User::query()
                        ->whereDoesntHave('roles', function ($query) {
                            $query->whereIn('role_id', [User::IS_ADMIN]);
                        })
                        ->where('status', 1)
                        ->count();

        $digital_product_count = DigitalProduct::query()->where('status', 1)->count();
        $digital_service_count = DigitalService::query()->where('status', 1)->count();

        $blog_count = Blog::query()->where('status', 1)->count();

        $count = [
            'user_count' => $user_count,
            'digital_product_count' => $digital_product_count,
            'digital_service_count' => $digital_service_count,
            'blog_count' => $blog_count,
        ];

        return view('home', compact('count'));
    }
}

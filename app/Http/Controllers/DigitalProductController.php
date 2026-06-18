<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class DigitalProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalProduct::query()->with('category')->where('status', 1);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->whereRaw('IF(on_sale = 1 AND price_for_sale IS NOT NULL, price_for_sale, price) >= ?', [$request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('IF(on_sale = 1 AND price_for_sale IS NOT NULL, price_for_sale, price) <= ?', [$request->max_price]);
        }

        if ($request->filled('category')) {
            if (is_array($request->category)) {
                $query->whereIn('category_id', $request->category);
            } else {
                $query->where('category_id', $request->category);
            }
        }

        if ($request->filled('on_sale')) {
            $query->where('on_sale', 1);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderByRaw('IF(on_sale = 1 AND price_for_sale IS NOT NULL, price_for_sale, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('IF(on_sale = 1 AND price_for_sale IS NOT NULL, price_for_sale, price) DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                default:
                    $query->orderBy('sort_order', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('sort_order', 'DESC');
        }

        $digitalproducts = $query->paginate(8)->withQueryString();

        $categorys = Category::query()->where('type', 'product')->where('status', 1)->pluck('name','slug');

        return view('digital-products.listing', compact('digitalproducts','categorys'));
    }

    public function show($slug)
    {
        $product = DigitalProduct::query()->with('category')->where('slug', $slug)
                                ->where('status', 1)
                                ->firstOrFail();

        $relatedProducts = DigitalProduct::query()->with('category')->where('category_id', $product->category_id)
                                        ->where('id', '!=', $product->id)
                                        ->where('status', 1)
                                        ->inRandomOrder()
                                        ->take(4)
                                        ->get();

        return view('digital-products.details', compact('product', 'relatedProducts'));
    }
}

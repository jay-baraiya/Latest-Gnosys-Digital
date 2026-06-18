<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DigitalService;
use Illuminate\Http\Request;

class DigitalServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalService::query()->with(['category','variants'])->where('status', 1);
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
            $query->orderBy('sort_order', 'ASC');
        }

        $digitalServices = $query->paginate(8)->withQueryString();

        $categorys = Category::query()->where('type', 'service')->where('status', 1)->pluck('name','slug');

        return view('digital-services.listing', compact('digitalServices','categorys'));
    }

    public function show($slug)
    {
        $service = DigitalService::query()
                                ->select(['id','name','slug','sku','category_id','description','short_description','price','price_for_sale','on_sale','badge','image','tags'])
                                ->with(['category:id,name,slug,type','variants:id,name,service_id,price,description,features','serviceFeatures:id,service_id,name'])
                                ->where('slug', $slug)
                                ->where('status', 1)
                                ->firstOrFail();

        $relatedServices = DigitalService::query()->with('category','variants')->where('category_id', $service->category_id)
                                        ->where('id', '!=', $service->id)
                                        ->where('status', 1)
                                        ->inRandomOrder()
                                        ->take(4)
                                        ->get();

        return view('digital-services.details', compact('service', 'relatedServices'));
    }
}

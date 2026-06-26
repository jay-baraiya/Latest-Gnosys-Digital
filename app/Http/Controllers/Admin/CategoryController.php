<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    protected $moduleName = 'Categories';
    protected $moduleUrl = 'admin.categories.index';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleUrl' => $this->moduleUrl,
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.categories')->only('create', 'store');
        $this->middleware('permission:edit.categories')->only('edit', 'update');
        $this->middleware('permission:delete.categories')->only('destroy');
        $this->middleware('permission:view.categories')->only('index', 'show');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::query()->with(['subcategory'])
            ->when(!empty($request->is_deleted), function ($q){
                $q->onlyTrashed();
            })
            ->when(!empty($request->input('search.value')), function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->input('search.value')}%");
                });
            })
            ->orderBy('id', 'DESC');

            return DataTables::eloquent($data)
                ->with('total_categories', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.categories.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.categories.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('name', function ($row) {
                    return $row->name ?? '-';
                })
                ->addColumn('sub_cat_id', function ($row) {
                    return $row->subcategory?->name ?? '-';
                })
                ->addColumn('type', function ($row) {
                    return ucfirst($row->type) ?? '-';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.categories.edit', encrypt($row->id)),
                        'show' => route('admin.categories.show', encrypt($row->id)),
                        'delete' => route('admin.categories.destroy', encrypt($row->id)),
                        'restore' => route('admin.categories.restore', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'is_deleted' => $request->is_deleted,
                    ])->render();
                })
                ->rawColumns(['status', 'name', 'type', 'sub_cat_id', 'actions'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $categorys = Category::query()->whereNull('sub_cat_id')->where('status', 1)->get();
        return view('admin.categories.form', compact('categorys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:product,service',
            'status' => 'required|in:1,0',
        ]);

        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'type' => $request->type,
                'sub_cat_id' => $request->sub_cat_id,
                'status' => $request->status ? 1 : 0,
            ]);

            return redirect()->route($this->moduleUrl)->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Log::error('Category Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create category. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $category = Category::findOrFail(decrypt($id));
        $categorys = Category::query()->whereNull('sub_cat_id')->where('status', 1)->get();
        return view('admin.categories.show', compact('category','categorys'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $category = Category::findOrFail(decrypt($id));
        $categorys = Category::query()->whereNull('sub_cat_id')->where('status', 1)->get();

        return view('admin.categories.form', compact('category', 'categorys'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('categories', 'name')->ignore(decrypt($id))],
            'type' => 'required|in:product,service',
            'status' => 'required|in:1,0',
        ]);

        try {
            $category = Category::findOrFail(decrypt($id));

            $category->update([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status ? 1 : 0,
                'sub_cat_id' => $request->sub_cat_id
            ]);


            return redirect()->route($this->moduleUrl)->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Category Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update category. Please try again later.');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $category = Category::withTrashed()->findOrFail(decrypt($request->id));
            $category->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $category->status == 1 ? 'Category activated successfully.' : 'Category deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Category Status Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::with(['products','services','blogs'])->findOrFail(decrypt($id));

            if ($category->products()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this category because it is currently assigned to one or more digital products.'
                ]);
            }

            if ($category->services()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this category because it is currently assigned to one or more digital services.'
                ]);
            }

            if ($category->blogs()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this category because it is currently assigned to one or more blogs.'
                ]);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Category Destroy Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function restore(string $id)
    {
        try {
            $category = Category::withTrashed()->findOrFail(decrypt($id));

            $category->restore();

            return response()->json([
                'success' => true,
                'message' => 'Category restored successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Category Restore Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ]);
        }
    }

    public function checkCategoryName(Request $request)
    {
        $query = Category::query()->where('name', $request->name);

        if ($request->filled('category_id')) {
            $query->where('id', '!=', $request->category_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }
}

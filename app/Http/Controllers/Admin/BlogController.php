<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    protected $moduleName = 'Blogs';

    protected $moduleUrl = 'admin.blogs.index';

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

        $this->middleware('permission:create.blogs')->only('create', 'store');
        $this->middleware('permission:edit.blogs')->only('edit', 'update');
        $this->middleware('permission:delete.blogs')->only('destroy');
        $this->middleware('permission:view.blogs')->only('index', 'show');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = Blog::query()->with(['category'])
            ->when(!empty($request->is_deleted), function ($q){
                $q->onlyTrashed();
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('id', $request->category_id);
                });
            })
            ->when(!empty($request->input('search.value')), function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->input('search.value')}%")
                    ->orWhere('description', 'like', "%{$request->input('search.value')}%");
                });
            })
            ->orderBy('id', 'DESC');

            return DataTables::eloquent($data)
                ->with('total_blogs', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.blogs.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.blogs.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->addColumn('name', function ($row) {
                    $name = $row->name ?? 'Unknown';

                    if (!empty($row->image)) {
                        $imagePath = filter_var($row->image, FILTER_VALIDATE_URL)
                            ? $row->image
                            : asset($row->image);
                    } else {
                        $imagePath = asset('assets/img/profiles/default.jpg');
                    }

                    return '<h6 class="d-flex align-items-center fs-14 fw-medium mb-0">
                                <a href="' . $imagePath . '" target="blank" class="avatar avatar-rounded me-2">
                                    <img src="' . $imagePath . '" alt="' . $name . '">
                                </a>
                                <a href="' . $imagePath . '" target="blank" class="d-flex flex-column">' . $name . '</a>
                            </h6>';
                })
                ->addColumn('category_id', function ($row) {
                    return $row?->category?->name ?? '-';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.blogs.edit', encrypt($row->id)),
                        'show' => route('admin.blogs.show', encrypt($row->id)),
                        'delete' => route('admin.blogs.destroy', encrypt($row->id)),
                        'restore' => route('admin.blogs.restore', encrypt($row->id)),
                        'id' => encrypt($row->id),
                        'is_deleted' => $request->is_deleted,
                    ])->render();
                })
                ->rawColumns(['status', 'name', 'sku', 'category_id', 'price', 'actions'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

        $categories = Category::active()->get();

        return view('admin.blogs.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'status' => 'required|in:1,0',
        ]);

        DB::beginTransaction();

        try {

            $imagePath = null;

            if ($request->hasFile('image')) {

                $request->validate(
                    [
                        'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:1024',
                    ],
                    [
                        'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
                        'image.max' => 'The image size must not exceed 1 MB.',
                    ]
                );

                $path = $request->file('image')->store('blogs', 'public');

                $imagePath = Storage::url($path);

            } elseif ($request->filled('image_url')) {
                $imagePath = $request->image_url;
            } else {
                $request->validate([
                    'image' => 'required',
                ]);
            }

            $blog = Blog::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'image' => $imagePath,
                'tags' => $request->tags,
                'status' => $request->status ? 1 : 0,
                'read_time' => $request->read_time,
            ]);

            DB::commit();

            return redirect()->route($this->moduleUrl)->with('success', 'Blog created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');

        $blog = Blog::findOrFail(decrypt($id));
        $categories = Category::active()->get();

        return view('admin.blogs.show', compact('blog','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');

        $blog = Blog::findOrFail(decrypt($id));
        $categories = Category::active()->get();

        return view('admin.blogs.form', compact('blog','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required',
            'description'       => 'required',
            'short_description' => 'required',
            'status'            => 'required|in:1,0',
        ]);

        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail(decrypt($id));

            $imagePath = $blog->image;

            if ($request->remove_existing_image == '1') {
                if ($blog->image && !filter_var($blog->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $blog->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }
                $imagePath = null;
            }

            if ($request->hasFile('image')) {
                if ($blog->image && !filter_var($blog->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $blog->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }

                $request->validate(
                    [
                        'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:1024',
                    ],
                    [
                        'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
                        'image.max' => 'The image size must not exceed 1 MB.',
                    ]
                );

                $path = $request->file('image')->store('blogs', 'public');
                $imagePath = Storage::url($path);

            } elseif ($request->filled('image_url')) {
                $imagePath = $request->image_url;
            }

            $blog->update([
                'name'              => $request->name,
                'slug'              => Str::slug($request->name),
                'category_id'       => $request->category_id,
                'short_description' => $request->short_description,
                'description'       => $request->description,
                'tags'              => $request->tags,
                'image'             => $imagePath,
                'status'            => $request->status ? 1 : 0,
                'read_time'         => $request->read_time,
            ]);

            DB::commit();

            return redirect()->route($this->moduleUrl)->with('success', 'Blog updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog Update Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'request' => $request->except(['image']),
            ]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $blog = Blog::withTrashed()->findOrFail(decrypt($request->id));
            $blog->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $blog->status == 1 ? 'Blog activated successfully.' : 'Blog deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Blog Status Update Error', [
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
            $blog = Blog::withTrashed()->findOrFail(decrypt($id));

            if ($blog->trashed()) {
                $blog->forceDelete();
                return response()->json([
                    'success' => true,
                    'message' => 'Blog and associated files were permanently deleted.'
                ]);
            }

            $blog->update(['status' => 0]);

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Blog Destroy Error', [
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

    public function restore(string $id)
    {
        try {
            $blog = Blog::withTrashed()->findOrFail(decrypt($id));

            $blog->update(['status' => 0]);
            $blog->restore();

            return response()->json([
                'success' => true,
                'message' => 'Blog restored successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Blog Restore Error', [
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
}

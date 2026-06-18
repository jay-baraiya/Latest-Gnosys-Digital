<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\ProcessProjectUpload;
use App\Models\Category;
use App\Models\CustomField;
use App\Models\CustomFieldType;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DigitalProductController extends Controller
{

    protected $moduleName = 'Digital Products';

    protected $moduleUrl = 'admin.digital.products.index';

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

        $this->middleware('permission:create.digital.products')->only('create', 'store');
        $this->middleware('permission:edit.digital.products')->only('edit', 'update');
        $this->middleware('permission:delete.digital.products')->only('destroy');
        $this->middleware('permission:view.digital.products')->only('index', 'show');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = DigitalProduct::query()->with(['category'])
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
                ->with('total_digital_products', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.digital.products.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.digital.products.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
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
                ->addColumn('sku', function ($row) {
                    return $row->sku ?? '-';
                })
                ->addColumn('category_id', function ($row) {
                    return $row?->category?->name ?? '-';
                })
                ->addColumn('price', function ($row) {
                    return $row?->price ?? '0.00';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.digital.products.edit', encrypt($row->id)),
                        'show' => route('admin.digital.products.show', encrypt($row->id)),
                        'delete' => route('admin.digital.products.destroy', encrypt($row->id)),
                        'restore' => route('admin.digital.products.restore', encrypt($row->id)),
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
        return view('admin.digital-products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

        $categories = Category::active()->get();
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();

        return view('admin.digital-products.form', compact('categories','customfieldtyeps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'price' => 'required',
            'status' => 'required|in:1,0',
            'project' => 'required|file|mimes:zip|max:1048576',
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

                $path = $request->file('image')->store('digital/products', 'public');

                $imagePath = Storage::url($path);

            } elseif ($request->filled('image_url')) {
                $imagePath = $request->image_url;

            } else {
                $request->validate([
                    'image' => 'required',
                ]);
            }

            $digitalproduct = DigitalProduct::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'price_for_sale' => $request->price_for_sale,
                'on_sale' => $request->on_sale,
                'badge' => $request->badge,
                'image' => $imagePath,
                'tags' => $request->tags,
                'sort_order' => $request->sort_order,
                'status' => $request->status ? 1 : 0,
            ]);

            if ($digitalproduct->id) {
                if ($request->hasFile('project')) {

                    $tempPath = $request->file('project')->store('temp_uploads', 'local');

                    ProcessProjectUpload::dispatch($digitalproduct->id, $tempPath);

                    $digitalproduct->update(['status' => 0]);
                }
            }

            if ($request->has('custom_field') && $digitalproduct->id) {
                $customFieldData = $request->input('custom_field', []);
                $customFieldData['recode_id'] = $digitalproduct->id;
                $customFieldData['service_id'] = $digitalproduct->id;

                $request->merge([
                    'custom_field' => $customFieldData,
                ]);

                $customFieldIds = CommonController::storeCustomFields($request);
            }

            DB::commit();

            return redirect()->route($this->moduleUrl)->with('success', 'Digital product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Digital Product Store Error', [
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
        $categories = Category::active()->get();
        $digitalproduct = DigitalProduct::findOrFail(decrypt($id));
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $customfields = CustomField::with(['fieldType'])->where('recode_id', decrypt($id))->get();

        return view('admin.digital-products.show', compact('categories','digitalproduct','customfieldtyeps','customfields'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');

        $categories = Category::active()->get();
        $digitalproduct = DigitalProduct::findOrFail(decrypt($id));
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $customfields = CustomField::with(['fieldType'])->where('recode_id', decrypt($id))->get();

        return view('admin.digital-products.form', compact('digitalproduct','categories','customfieldtyeps','customfields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'required',
            'category_id'       => 'required',
            'description'       => 'required',
            'short_description' => 'required',
            'price'             => 'required',
            'status'            => 'required|in:1,0',
        ]);

        DB::beginTransaction();

        try {
            $digitalproduct = DigitalProduct::findOrFail(decrypt($id));

            $imagePath = $digitalproduct->image;

            if ($request->remove_existing_image == '1') {
                if ($digitalproduct->image && !filter_var($digitalproduct->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $digitalproduct->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }
                $imagePath = null;
            }

            if ($request->hasFile('image')) {
                if ($digitalproduct->image && !filter_var($digitalproduct->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $digitalproduct->image);
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

                $path = $request->file('image')->store('digital/products', 'public');
                $imagePath = Storage::url($path);

            } elseif ($request->filled('image_url')) {
                $imagePath = $request->image_url;
            }

            if ($request->hasFile('project')) {

                $request->validate(
                    ['project' => 'required|file|mimes:zip|max:1048576'],
                    [
                        'project.mimes' => 'Only .zip files are allowed for the project.',
                        'project.max' => 'The project file size must not exceed 1 GB.',
                    ]
                );

                if ($digitalproduct->project && Storage::disk('local')->exists($digitalproduct->project)) {
                    Storage::disk('local')->delete($digitalproduct->project);
                }

                $tempPath = $request->file('project')->store('temp_uploads', 'local');

                ProcessProjectUpload::dispatch($digitalproduct->id, $tempPath);

                // $request->merge(['status' => 0]);
            }

            $digitalproduct->update([
                'name'              => $request->name,
                'slug'              => Str::slug($request->name),
                'sku'               => $request->sku,
                'category_id'       => $request->category_id,
                'description'       => $request->description,
                'short_description' => $request->short_description,
                'price'             => $request->price,
                'price_for_sale'    => $request->price_for_sale,
                'on_sale'           => $request->on_sale ? 1 : 0,
                'badge'             => $request->badge,
                'image'             => $imagePath,
                'sort_order'        => $request->sort_order,
                'status'            => $request->status ? 1 : 0,
            ]);

            if ($request->has('custom_field')) {
                $customFieldIds = CommonController::storeCustomFields($request);
            }

            DB::commit();

            return redirect()->route($this->moduleUrl)->with('success', 'Digital product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Digital Product Update Error', [
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
            $digitalProduct = DigitalProduct::withTrashed()->findOrFail(decrypt($request->id));
            $digitalProduct->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $digitalProduct->status == 1 ? 'Digital product activated successfully.' : 'Digital product deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Digital Product Status Update Error', [
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
            $digitalProduct = DigitalProduct::withTrashed()->findOrFail(decrypt($id));

            if ($digitalProduct->trashed()) {
                $digitalProduct->forceDelete();
                return response()->json([
                    'success' => true,
                    'message' => 'Product and associated files were permanently deleted.'
                ]);
            }

            $digitalProduct->update(['status' => 0]);

            $digitalProduct->delete();

            return response()->json([
                'success' => true,
                'message' => 'Digital product deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Digital Product Destroy Error', [
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
            $digitalProduct = DigitalProduct::withTrashed()->findOrFail(decrypt($id));

            $digitalProduct->restore();

            return response()->json([
                'success' => true,
                'message' => 'Digital product restored successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Digital Product Restore Error', [
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

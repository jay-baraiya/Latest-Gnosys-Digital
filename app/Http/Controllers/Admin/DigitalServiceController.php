<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CustomField;
use App\Models\CustomFieldType;
use App\Models\DigitalService;
use App\Models\ServiceFeature;
use App\Models\ServiceVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DigitalServiceController extends Controller
{

    protected $moduleName = 'Digital Services';

    protected $moduleUrl = 'admin.digital.services.index';

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

        $this->middleware('permission:create.digital.services')->only('create', 'store');
        $this->middleware('permission:edit.digital.services')->only('edit', 'update');
        $this->middleware('permission:delete.digital.services')->only('destroy');
        $this->middleware('permission:view.digital.services')->only('index', 'show');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $searchValue = $request->input('search.value');

            $data = DigitalService::query()->with(['category'])
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
                ->with('total_digital_services', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="' . route('admin.digital.services.updateStatus', ['id' => encrypt($row->id), 'status' => 0]) . '" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active</a>';
                    } else {
                        return '<a href="' . route('admin.digital.services.updateStatus', ['id' => encrypt($row->id), 'status' => 1]) . '" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
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
                                <a href="'.$imagePath.'" target="blank" class="avatar avatar-rounded me-2">
                                    <img src="' . $imagePath . '" alt="' . $name . '">
                                </a>
                                <a href="'.$imagePath.'" class="d-flex flex-column" target="blank">' . $name . '</a>
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
                        'edit' => route('admin.digital.services.edit', encrypt($row->id)),
                        'show' => route('admin.digital.services.show', encrypt($row->id)),
                        'delete' => route('admin.digital.services.destroy', encrypt($row->id)),
                        'restore' => route('admin.digital.services.restore', encrypt($row->id)),
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
        return view('admin.digital-services.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');

        $categories = Category::active()->get();
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();

        return view('admin.digital-services.form', compact('categories','customfieldtyeps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:digital_services,name',
            'sku' => 'required|unique:digital_services,sku',
            'category_id' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'price' => 'required',
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

                $path = $request->file('image')->store('digital/services', 'public');

                $imagePath = Storage::url($path);

            } elseif ($request->filled('image_url')) {
                $imagePath = $request->image_url;

            } else {
                $request->validate([
                    'image' => 'required',
                ]);
            }

            $digitalservice = DigitalService::create([
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

            $this->storeAllMultipleData($request, $digitalservice);

            DB::commit();

            return redirect()->route($this->moduleUrl)->with('success', 'Digital service created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Digital service Store Error', [
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
        $digitalservice = DigitalService::findOrFail(decrypt($id));
        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $customfields = CustomField::with(['fieldType'])->where('recode_id', decrypt($id))->get();

        return view('admin.digital-services.show', compact('digitalservice','categories','customfieldtyeps','customfields'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');

        $categories = Category::active()->get();
        $digitalservice = DigitalService::with(['variants','serviceFeatures:id,service_id,name'])->findOrFail(decrypt($id));

        $customfieldtyeps = CustomFieldType::query()->where('status', 1)->get();
        $customfields = CustomField::with(['fieldType'])->where('recode_id', decrypt($id))->get();

        return view('admin.digital-services.form', compact('digitalservice','categories','customfieldtyeps','customfields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate([
                'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('digital_services', 'name')->ignore(decrypt($id)),
            ],
            'sku' => [
                'required',
                Rule::unique('digital_services', 'sku')->ignore(decrypt($id)),
            ],
            'category_id'       => 'required',
            'description'       => 'required',
            'short_description' => 'required',
            'price'             => 'required',
            'status'            => 'required|in:1,0',
        ]);

        DB::beginTransaction();

        try {
            $digitalservice = DigitalService::findOrFail(decrypt($id));

            $imagePath = $digitalservice->image;

            if ($request->remove_existing_image == '1') {
                if ($digitalservice->image && !filter_var($digitalservice->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $digitalservice->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }
                $imagePath = null;
            }

            if ($request->hasFile('image')) {
                if ($digitalservice->image && !filter_var($digitalservice->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $digitalservice->image);
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

                $path = $request->file('image')->store('digital/services', 'public');
                $imagePath = Storage::url($path);

            } elseif ($request->filled('image_url')) {
                $imagePath = $request->image_url;
            }

            $digitalservice->update([
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
                'tags'              => $request->tags,
                'sort_order'        => $request->sort_order,
                'status'            => $request->status ? 1 : 0,
            ]);

            $this->storeAllMultipleData($request, $digitalservice);

            DB::commit();

            return redirect()->route($this->moduleUrl)->with('success', 'Digital service updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Digital service Update Error', [
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
            $digitalservice = DigitalService::withTrashed()->findOrFail(decrypt($request->id));
            $digitalservice->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $digitalservice->status == 1 ? 'Digital service activated successfully.' : 'Digital service deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Digital service Status Update Error', [
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
            $digitalservice = DigitalService::withTrashed()->findOrFail(decrypt($id));

            if ($digitalservice->trashed()) {
                $digitalservice->forceDelete();
                return response()->json([
                    'success' => true,
                    'message' => 'Service and associated files were permanently deleted.'
                ]);
            }

            $digitalservice->update(['status' => 0]);

            $digitalservice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Digital service deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Digital service Destroy Error', [
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
            $digitalProduct = DigitalService::withTrashed()->findOrFail(decrypt($id));

            $digitalProduct->restore();

            return response()->json([
                'success' => true,
                'message' => 'Digital service restored successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Digital Service Restore Error', [
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

    public function checkServiceName(Request $request)
    {
        $query = DigitalService::query()->where('name', $request->name);

        if ($request->filled('service_id')) {
            $query->where('id', '!=', $request->service_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function checkServiceSku(Request $request)
    {
        $query = DigitalService::query()->where('sku', $request->sku);

        if ($request->filled('service_id')) {
            $query->where('id', '!=', $request->service_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function storeAllMultipleData($request, $digitalservice) {
        $customFieldIds = [];
        if ($request->has('custom_field') && $digitalservice->id) {
            $customFieldData = $request->input('custom_field', []);
            $customFieldData['recode_id'] = $digitalservice->id;
            $customFieldData['service_id'] = $digitalservice->id;

            $request->merge([
                'custom_field' => $customFieldData,
            ]);

            $customFieldIds = CommonController::storeCustomFields($request);
        }

        $variantIds = [];
        if ($request->has('variant') && $digitalservice->id) {

            $variantData = $request->input('variant', []);
            $variantData['recode_id'] = $digitalservice->id;
            $variantData['service_id'] = $digitalservice->id;

            $request->merge([
                'variant' => $variantData,
            ]);

            $variantIds = CommonController::storeServiceVariants($request);
        }

        $featureIds = [];
        if ($request->has('features') && $digitalservice->id) {

            $featuresData = $request->input('features', []);
            $featuresData['recode_id'] = $digitalservice->id;
            $featuresData['service_id'] = $digitalservice->id;

            $request->merge([
                'features' => $featuresData,
            ]);

            $featureIds = CommonController::storeServiceFeatures($request);
        }
    }
}

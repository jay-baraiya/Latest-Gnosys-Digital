<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\DigitalService;
// If Event model is created in future, use it here.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    protected $moduleName = 'Coupons';

    protected $moduleUrl = 'admin.coupons.index';

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

        $this->middleware('permission:create.coupons')->only('create', 'store');
        $this->middleware('permission:edit.coupons')->only('edit', 'update');
        $this->middleware('permission:delete.coupons')->only('destroy');
        $this->middleware('permission:view.coupons')->only('index', 'show');
    }

    public function index()
    {
        return view('admin.coupons.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Coupon::query()
                ->when(! empty($request->input('search.value')), function ($query) use ($request) {
                    $query->where('code', 'like', "%{$request->input('search.value')}%");
                })
                ->orderBy('id', 'DESC');

            return DataTables::eloquent($data)
                ->with('total_coupons', $data->count())
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<a href="'.route('admin.coupons.updateStatus', ['id' => encrypt($row->id), 'status' => 0]).'" class="badge badge-pill badge-status bg-success" id="statusUpdate">Active </a>';
                    } else {
                        return '<a href="'.route('admin.coupons.updateStatus', ['id' => encrypt($row->id), 'status' => 1]).'" class="badge badge-pill badge-status bg-danger" id="statusUpdate">Inactive</a>';
                    }
                })
                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->addColumn('value', function ($row) {
                    if ($row->type == 'percentage') {
                        return $row->value.'%';
                    }

                    return '$'.number_format($row->value, 2);
                })
                ->addColumn('used_max', function ($row) {
                    $max = $row->usage_limit ?: 'Unlimited';

                    return $row->used_count.' / '.$max;
                })
                ->editColumn('expires_at', function ($row) {
                    return $row->expires_at ? $row->expires_at->format('Y-m-d') : 'Never expire';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.components.action-links', [
                        'edit' => route('admin.coupons.edit', encrypt($row->id)),
                        'show' => route('admin.coupons.show', encrypt($row->id)),
                        'delete' => route('admin.coupons.destroy', encrypt($row->id)),
                        'id' => encrypt($row->id),
                    ])->render();
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        $services = DigitalService::where('status', 1)->get();
        $events = [];

        return view('admin.coupons.form', compact('services', 'events'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required|unique:coupons,code|regex:/^[A-Z0-9]+$/',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'applies_to' => 'required|in:credits,events,both',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_user' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'status' => 'required|in:0,1',
        ]);

        $data = $request->except(['_token', 'never_expire']);

        $data['code'] = strtoupper($data['code']);
        $data['service_ids'] = $request->has('service_ids') ? $request->service_ids : null;
        $data['event_ids'] = $request->has('event_ids') ? $request->event_ids : null;
        $data['created_by'] = $this->authUser->id;

        // Never expire logic
        if ($request->has('never_expire') && $request->never_expire == '1') {
            $data['starts_at'] = null;
            $data['expires_at'] = null;
        }

        Coupon::create($data);

        return redirect()->route($this->moduleUrl)->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));

        $services = DigitalService::where('status', 1)->get();

        $events = [];

        return view('admin.coupons.form', compact('coupon', 'services', 'events'));
    }

    public function show($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));

        $services = [];
        if (! empty($coupon->service_ids)) {
            $services = DigitalService::whereIn('id', $coupon->service_ids)->get();
        }

        $events = [];

        return view('admin.coupons.show', compact('coupon', 'services', 'events'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));

        $request->validate([
            'code' => 'required|regex:/^[A-Z0-9]+$/|unique:coupons,code,'.$coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'applies_to' => 'required|in:credits,events,both',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_user' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'status' => 'required|in:0,1',
        ]);

        $data = $request->except(['_token', '_method', 'never_expire']);

        $data['code'] = strtoupper($data['code']);
        $data['service_ids'] = $request->has('service_ids') ? $request->service_ids : null;
        $data['event_ids'] = $request->has('event_ids') ? $request->event_ids : null;

        if ($request->has('never_expire') && $request->never_expire == '1') {
            $data['starts_at'] = null;
            $data['expires_at'] = null;
        }

        $coupon->update($data);

        return redirect()->route($this->moduleUrl)->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));
        $coupon->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon deleted successfully.',
        ]);
    }

    public function updateStatus(Request $request)
    {
        try {
            $coupon = Coupon::findOrFail(decrypt($request->id));

            $coupon->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $coupon->status == 1 ? 'Coupon activated successfully.' : 'Coupon deactivated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Coupon Status Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
            ]);
        }
    }

    public function checkCode(Request $request)
    {
        $query = Coupon::query()->where('code', $request->code);

        if ($request->filled('coupon_id')) {
            $query->where('id', '!=', $request->coupon_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }
}

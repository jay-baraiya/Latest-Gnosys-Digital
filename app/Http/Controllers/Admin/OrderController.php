<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Designation;
use App\Models\DigitalProduct;
use App\Models\DigitalService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\ServiceVariant;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\RealTimeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $moduleName = 'Orders';
    protected $moduleUrl = 'admin.orders.index';

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

        // $this->middleware('permission:create.users')->only('create', 'store');
        // $this->middleware('permission:edit.users')->only('edit', 'update');
        // $this->middleware('permission:delete.users')->only('destroy');
        // $this->middleware('permission:view.users')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.order.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $excludedRoles = [User::IS_ADMIN];

            if (isset($request->is_buyer) && $request->is_buyer == 0) {
                $excludedRoles[] = User::IS_BUYER;
            }

            $query = Order::query()
                ->with([
                    'tickets:id,ticket_number,datetime,order_id,user_id,developer_id,order_item_id,status,cancelled_by,cancel_reason',
                    'user:id,name',
                    'tickets.orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'
                ])
                ->select(['id', 'user_id', 'order_number', 'date_time', 'total_amount']);

            return DataTables::eloquent($query)
                ->with('total_tasks', $query->count())
                ->addIndexColumn()
                ->addColumn('order_number', function ($row) {
                    return '<span class="fw-semibold">#' . $row->order_number . '</span>';
                })
                ->addColumn('order_date', function ($row) {
                    return Carbon::parse($row->date_time)->format('d-m-Y');
                })
                ->addColumn('customer_name', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('total_amount', function ($row) {
                    return '$' . number_format($row->total_amount, 2);
                })
                ->addColumn('tickets_list', function ($row) {
                    $count = $row->tickets->count();

                    if ($count === 0) {
                        return '<span class="text-muted">No tickets</span>';
                    }

                    return '<button class="btn btn-sm btn-outline-info view-tickets-btn" data-order-id="' . encrypt($row->id) . '">
                            <i class="ti ti-ticket me-1"></i> See Tickets (' . $count . ')
                        </button>';
                })
                ->addColumn('actions', function ($row) use ($request) {
                    return view('admin.components.action-links', [
                        'edit'       => route('admin.orders.edit', encrypt($row->id)),
                        'show'       => route('admin.orders.show', encrypt($row->id)),
                        'delete'     => route('admin.orders.destroy', encrypt($row->id)),
                        'id'         => encrypt($row->id),
                    ])->render();
                })
                ->rawColumns(['order_number', 'tickets_list', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view()->share('action', 'Create');
        $users       = User::query()->where('status', 1)->whereNotIn('id', [User::IS_ADMIN])->get(['id', 'name', 'email']);
        $countries   = Country::query()->orderBy('name')->get(['id', 'name']);
        $products    = DigitalProduct::query()->whereNull('deleted_at')->get(['id', 'name', 'price']);
        $services    = DigitalService::with('variants')->where('status', 1)->get();

        $order_number = 'ORD-' . strtoupper(substr(uniqid(), -6));

        return view('admin.order.form', compact('users', 'countries', 'products', 'services', 'order_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_number' => ['required', 'string', 'max:255', Rule::unique('orders')],
            'date_time' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'billing_first_name' => 'nullable|string|max:255',
            'billing_phone' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_method' => 'nullable|string|in:stripe,paypal,cod',
            'payment_status' => 'required|string|in:pending,paid,failed,refunded,success',
            'order_notes' => 'nullable|string',

            'product_type' => 'required|array',
            'product_type.*' => 'required|string|in:product,service',
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer',
            'variant_id' => 'nullable|array',
            'variant_id.*' => 'nullable|integer',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->user_id,
                'order_number' => $request->order_number,
                'date_time' => Carbon::parse($request->date_time),
                'subtotal' => $request->subtotal,
                'total_amount' => $request->total_amount,
                'billing_first_name' => $request->billing_first_name,
                'billing_phone' => $request->billing_phone,
                'billing_country' => $request->billing_country,
                'billing_state' => $request->billing_state,
                'billing_city' => $request->billing_city,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'order_notes' => $request->order_notes,
            ]);

            foreach ($request->product_id as $index => $productId) {
                $type = $request->product_type[$index];
                $qty = $request->quantity[$index];
                $price = $request->price[$index];
                $variantId = $request->variant_id[$index] ?? null;

                $productName = '';
                $variantName = null;

                if ($type === 'product') {
                    $prod = DigitalProduct::find($productId);
                    $productName = $prod ? $prod->name : 'Unknown Product';
                } else {
                    $serv = DigitalService::find($productId);
                    $productName = $serv ? $serv->name : 'Unknown Service';
                    if ($variantId) {
                        $variant = ServiceVariant::find($variantId);
                        $variantName = $variant ? $variant->name : null;
                    }
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'product_type' => $type,
                    'variant_id' => $variantId,
                    'variant_name' => $variantName,
                    'product_price' => $price,
                    'product_qty' => $qty,
                    'total_amount' => $price * $qty,
                ]);

                // Create ticket for the order item
                Ticket::create([
                    'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
                    'datetime' => now(),
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'user_id' => $order->user_id,
                    'status' => 'pending',
                ]);
            }

            DB::commit();
            return redirect()->route($this->moduleUrl)->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create order. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        view()->share('action', 'View');
        $user = User::with(['country', 'state', 'city'])->findOrFail(decrypt($id));
        $designations = Designation::query()->where('status', 1)->get();
        return view('admin.order.show', compact('user', 'designations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        view()->share('action', 'Edit');
        $order = Order::with('orderItems')->findOrFail(decrypt($id));

        $users     = User::query()->where('status', 1)->whereNotIn('id', [User::IS_ADMIN])->get(['id', 'name', 'email']);
        $countries = Country::query()->orderBy('name')->get(['id', 'name']);
        $products  = DigitalProduct::query()->whereNull('deleted_at')->get(['id', 'name', 'price']);
        $services  = DigitalService::with('variants')->where('status', 1)->get();

        return view('admin.order.form', compact('order', 'users', 'countries', 'products', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orderId = decrypt($id);
        $order = Order::findOrFail($orderId);

        $validatedData = $request->validate([
            'order_number' => ['required', 'string', 'max:255', Rule::unique('orders')->ignore($order->id)],
            'date_time' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'billing_first_name' => 'nullable|string|max:255',
            'billing_phone' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_city' => 'nullable|string|max:255',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_method' => 'nullable|string|in:stripe,paypal,cod',
            'payment_status' => 'required|string|in:pending,paid,failed,refunded,success',
            'order_notes' => 'nullable|string',

            'product_type' => 'required|array',
            'product_type.*' => 'required|string|in:product,service',
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer',
            'variant_id' => 'nullable|array',
            'variant_id.*' => 'nullable|integer',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $order->update([
                'user_id' => $request->user_id,
                'order_number' => $request->order_number,
                'date_time' => Carbon::parse($request->date_time),
                'subtotal' => $request->subtotal,
                'total_amount' => $request->total_amount,
                'billing_first_name' => $request->billing_first_name,
                'billing_phone' => $request->billing_phone,
                'billing_country' => $request->billing_country,
                'billing_state' => $request->billing_state,
                'billing_city' => $request->billing_city,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'order_notes' => $request->order_notes,
            ]);

            $updatedItemIds = [];

            foreach ($request->product_id as $index => $productId) {
                $type = $request->product_type[$index];
                $qty = $request->quantity[$index];
                $price = $request->price[$index];
                $variantId = $request->variant_id[$index] ?? null;
                $orderItemId = $request->order_item_id[$index] ?? null;

                $productName = '';
                $variantName = null;

                if ($type === 'product') {
                    $prod = DigitalProduct::find($productId);
                    $productName = $prod ? $prod->name : 'Unknown Product';
                } else {
                    $serv = DigitalService::find($productId);
                    $productName = $serv ? $serv->name : 'Unknown Service';
                    if ($variantId) {
                        $variant = ServiceVariant::find($variantId);
                        $variantName = $variant ? $variant->name : null;
                    }
                }

                if ($orderItemId) {
                    $orderItem = OrderItem::where('order_id', $order->id)->find($orderItemId);
                    if ($orderItem) {
                        $orderItem->update([
                            'product_id' => $productId,
                            'product_name' => $productName,
                            'product_type' => $type,
                            'variant_id' => $variantId,
                            'variant_name' => $variantName,
                            'product_price' => $price,
                            'product_qty' => $qty,
                            'total_amount' => $price * $qty,
                        ]);
                        $updatedItemIds[] = $orderItem->id;
                    }
                } else {
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'product_name' => $productName,
                        'product_type' => $type,
                        'variant_id' => $variantId,
                        'variant_name' => $variantName,
                        'product_price' => $price,
                        'product_qty' => $qty,
                        'total_amount' => $price * $qty,
                    ]);
                    $updatedItemIds[] = $orderItem->id;

                    // Create ticket for new order item
                    Ticket::create([
                        'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
                        'datetime' => now(),
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'user_id' => $order->user_id,
                        'status' => 'pending',
                    ]);
                }
            }

            // Delete removed order items
            OrderItem::where('order_id', $order->id)->whereNotIn('id', $updatedItemIds)->delete();

            // Delete tickets for removed items
            Ticket::where('order_id', $order->id)->whereNull('order_item_id')->delete();

            // Update user_id on existing tickets if the order user has changed
            Ticket::where('order_id', $order->id)->update(['user_id' => $order->user_id]);

            DB::commit();
            return redirect()->route($this->moduleUrl)->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update order. Please try again later.');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $user = Order::withTrashed()->findOrFail(decrypt($request->id));
            $user->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $user->status == 1 ? 'Order activated successfully.' : 'Order deactivated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Order Status Update Error', [
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
            $user = Order::findOrFail(decrypt($id));
            $user->update(['status' => 0]);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Order Destroy Error', [
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
            $user = User::withTrashed()->findOrFail(decrypt($id));

            $user->restore();

            return response()->json([
                'success' => true,
                'message' => 'User restored successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('User Restore Error', [
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

    public function checkEmail(Request $request)
    {
        $query = User::query()->where('email', $request->email);

        if ($request->filled('user_id')) {
            $query->where('id', '!=', $request->user_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function checkPhone(Request $request)
    {
        $query = User::query()->where('phone', $request->phone);

        if ($request->filled('user_id')) {
            $query->where('id', '!=', $request->user_id);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function getOrderTickets(Request $request)
    {
        try {
            $orderId = decrypt($request->order_id);

            $order = Order::with([
                'user:id,name',
                'orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price,total_amount',
            ])->select([
                'id',
                'user_id',
                'order_number',
                'date_time'
            ])->findOrFail($orderId);

            $html = view('admin.order.ticket_list', compact('order'))->render();

            return response()->json([
                'status' => 'success',
                'html'   => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDevUser()
    {
        $data = User::query()
            ->with(['roles'])
            ->where('status', 1)
            ->whereNotIn('id', [User::IS_ADMIN])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function assignDevUser(Request $request)
    {
        $ticket_id = $request->ticket_id;
        $developer_id = $request->developer_id;

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            $ticket->developer_id = $developer_id;

            $ticket->update();

            return response()->json([
                'success' => true,
                'message' => 'Developer assigned successfully',
                'data' => $ticket
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ticket not found'
        ], 404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->has('order_product_id')) {
            return redirect()->route('home');
        }

        $id = Auth::id();
        DB::beginTransaction();
        try {

            $isSameAddress = $request->has('use_same_address_for_billing') && $request->use_same_address_for_billing == 1;

            $commonAddressData = [
                'billing_first_name'   => $request->first_name,
                'billing_address'      => $request->billing_address,
                'billing_country'      => $request->billing_country,
                'billing_state'        => $request->billing_state,
                'billing_city'         => $request->billing_city,
                'billing_phone'        => $request->phone,
            ];

            $order = Order::create([
                'user_id'              => $id ?? null,
                'guest_email'          => $request->email,
                'order_number'         => 'ORD-' . strtoupper(substr(uniqid(), -6)),
                'subtotal'             => $request->order_product_grand_total,
                'total_amount'         => $request->order_product_grand_total,
                'status'               => 'pending',
                'payment_status'       => 'success',
                'date_time'            => Carbon::now(),
                ...$commonAddressData
            ]);

            if ($order->id) {
                $this->createOrderTicket($order);
            }

            $address = Address::updateOrInsert([
                'user_email'           => $request->email
            ],
            [
                'user_id'              => auth()->check() ? auth()->id() : null,
                'user_email'           => $request->email,
                ...$commonAddressData
            ]);

            if ($request->has('order_product_id') && is_array($request->order_product_id)) {
                $orderItems = [];

                foreach ($request->order_product_id as $index => $productId) {
                    $orderItems[] = [
                        'order_id'      => $order->id,
                        'product_id'    => decrypt($productId),
                        'product_name'  => !empty($request->order_product_title[$index]) ? $request->order_product_title[$index] : null,
                        'product_price' => !empty($request->order_product_price[$index]) ? $request->order_product_price[$index] : null,
                        'product_qty'   => !empty($request->order_product_qty[$index]) ? $request->order_product_qty[$index] : null,
                        'total_amount'  => !empty($request->order_product_total_amount[$index]) ? $request->order_product_total_amount[$index] : null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];

                    if (!empty($request->order_product_variant_id[$index])) {
                        $orderItems[$index]['variant_id'] = decrypt($request->order_product_variant_id[$index]);
                    } else {
                        $orderItems[$index]['variant_id'] = '';
                    }

                    if (!empty($request->order_product_variant_name[$index])) {
                        $orderItems[$index]['variant_name'] = ($request->order_product_variant_name[$index]);
                    } else {
                        $orderItems[$index]['variant_name'] = '';
                    }

                    if (!empty($request->order_product_type[$index])) {
                        $orderItems[$index]['product_type'] = ($request->order_product_type[$index]);
                    } else {
                        $orderItems[$index]['product_type'] = '';
                    }
                }

                OrderItem::insert($orderItems);
                $pids = array_column($orderItems, 'product_id');
                $package_ids = array_column($orderItems, 'package_id');

                if ($order->id) {
                    $user_wallet = Auth::user()?->balance;

                    $balance = $user_wallet?->balance ?? 0;
                    $order_total = $order->order_product_grand_total ?? 0;
                    $netBalance = $balance - $order_total;

                    Wallet::query()
                            ->where('user_id', Auth::id())
                            ->update(['balance' => $netBalance]);
                }

            }

            if (auth()->check()) {
                Cart::query()->where('user_id', $id)->delete();
            } else {
                session()->forget('cart');
            }

            DB::commit();

            return redirect()->route('orders.thank.you')->with('success', 'Order created successfully!');

            // return response()->json([
            //     'status'       => 'success',
            //     'message'      => 'Order created successfully!',
            //     'data' => [
            //         'order_id' => $order->id,
            //         'order_number' => $order->order_number,
            //         'order_grand_total' => $request->order_product_grand_total,
            //         'transection_id' => $order->order_number
            //     ]
            // ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Order Error => '. $th->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while placing your order.');

            // return response()->json([
            //     'status'  => 'error',
            //     'message' => 'Something went wrong while placing your order.'
            // ], 500);
        }
    }

    public function thankYouIndex(Request $request) {
        return view('thank-you');
    }

    public function getOrderItemList(Request $request) {
        try {
            $realOrderId = decrypt($request->order_id);
        } catch (DecryptException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid Order ID'], 400);
        }

        $order = Order::with('orderItems')->find($realOrderId);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $html = view('profile.partials.order_items_table', compact('order'))->render();

        // JSON રિસ્પોન્સમાં HTML મોકલો
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function createOrderTicket($order)
    {
        if ($order->id && $order->payment_status === 'success') {
            Ticket::create([
                'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
                'datetime' => now(),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => 'pending'
            ]);
        }
    }
}

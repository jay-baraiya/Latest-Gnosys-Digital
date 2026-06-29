<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email',
            'billing_country' => 'required',
            'billing_address' => 'required',
            'billing_state' => 'required',
            'billing_city' => 'required',
        ]);

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

            $address = Address::updateOrInsert([
                'user_email'           => $request->email
            ],
            [
                'user_id'              => auth()->check() ? auth()->id() : null,
                'user_email'           => $request->email,
                ...$commonAddressData
            ]);

            if ($request->has('order_product_id') && is_array($request->order_product_id) && $order->id) {
                $orderItems = [];

                foreach ($request->order_product_id as $index => $productId) {
                    $orderItem = OrderItem::create([
                        'order_id'      => $order->id,
                        'product_id'    => decrypt($productId),
                        'product_name'  => !empty($request->order_product_title[$index]) ? $request->order_product_title[$index] : null,
                        'product_price' => !empty($request->order_product_price[$index]) ? $request->order_product_price[$index] : null,
                        'product_qty'   => !empty($request->order_product_qty[$index]) ? $request->order_product_qty[$index] : null,
                        'total_amount'  => !empty($request->order_product_total_amount[$index]) ? $request->order_product_total_amount[$index] : null,

                        'variant_id'    => !empty($request->order_product_variant_id[$index]) ? decrypt($request->order_product_variant_id[$index]) : null,
                        'variant_name'  => !empty($request->order_product_variant_name[$index]) ? $request->order_product_variant_name[$index] : null,
                        'product_type'  => !empty($request->order_product_type[$index]) ? $request->order_product_type[$index] : null,
                    ]);
                }

                if ($order->id) {

                    Ticket::create([
                        'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
                        'datetime'      => now(),
                        'order_id'      => $order->id,
                        'user_id'       => $order->user_id,
                        'email'         => auth()->user()->email,
                        'name'          => auth()->user()->name,
                        'status'        => 'pending',
                        'priority'      => 'Low'
                    ]);

                    $user_wallet = Auth::user()?->balance;
                    $balance = $user_wallet?->balance ?? 0;
                    $order_total = $order->total_amount ?? 0;
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

            // 3. Setup PayPal Integration & Redirect
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.success', ['order_id' => encrypt($order->id)]),
                    "cancel_url" => route('paypal.cancel', ['order_id' => encrypt($order->id)]),
                ],
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($request->order_product_grand_total, 2, '.', '')
                        ]
                    ]
                ]
            ]);

            // Redirect user to PayPal
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
            }

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

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $order = Order::find(decrypt($request->order_id));

            if($order) {
                // Update Order Status
                $order->update(['transaction_id' => $response['id'],'getway_response' => json_encode($response),'payment_status' => 'success']);

                // Wallet Deduction Logic (Moved from store method)
                // if ($order->user_id) {
                //     $user = User::find($order->user_id);
                //     $balance = $user?->balance?->balance ?? 0;
                //     $order_total = $order->total_amount ?? 0;
                //     $netBalance = $balance - $order_total;

                //     Wallet::query()
                //         ->where('user_id', $order->user_id)
                //         ->update(['balance' => $netBalance]);
                // }

                // Cart Clearing Logic (Moved from store method)
                if ($order->user_id) {
                    Cart::query()->where('user_id', $order->user_id)->delete();
                } else {
                    session()->forget('cart');
                }

                return redirect()->route('orders.thank.you')->with('success', 'Order created and payment successful!');
            }
        }

        return redirect()->route('home')->with('error', 'Payment declined or incomplete!');
    }

    // 5. Handle Cancelled Payment
    public function paypalCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        if($order) {
            $order->update(['payment_status' => 'failed']);
        }

        // Return them back to the cart or checkout page so they can try again
        return redirect()->route('home')->with('error', 'You cancelled the payment process.');
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

}

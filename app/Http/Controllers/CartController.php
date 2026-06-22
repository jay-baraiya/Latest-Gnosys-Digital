<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Country;
use App\Models\DigitalProduct;
use App\Models\DigitalService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::id();

        $carts = collect();
        $grandTotal = 0;

        if ($id) {
            $carts = Cart::query()->where('user_id', $id)->get();
            $grandTotal = Cart::query()->where('user_id', $id)->sum('total_amount');
        } else {
            $sessionCarts = session()->get('cart', []);

            foreach ($sessionCarts as $item) {

                $cartItem = (object) [
                    'product_id'    => $item['id'],
                    'product_title' => $item['title'],
                    'variant_id' => $item['variant_id'],
                    'variant_name' => $item['variant_name'],
                    'product_img'   => $item['image'],
                    'product_type'  => $item['type'],
                    'product_price' => $item['price'],
                    'product_qty'   => $item['qty'],
                    'total_amount'   => $item['qty'] * $item['price'],
                ];

                $carts->push($cartItem);
                $grandTotal += ($item['price'] * $item['qty']);
            }
        }

        return view('cart', compact('carts', 'grandTotal'));
    }

    public function checkoutIndex(Request $request) {
        $id = Auth::id();

        $address = Address::query()->where('user_id', $id)->first();

        $countrys = Country::all();

        $carts = collect();
        $grandTotal = 0;

        if ($id) {
            $carts = Cart::query()->where('user_id', $id)->get();
            $grandTotal = Cart::query()->where('user_id', $id)->sum('total_amount');
        } else {
            $sessionCarts = session()->get('cart', []);

            $price = 0;
            foreach ($sessionCarts as $item) {

                $cartItem = (object) [
                    'product_id'    => $item['id'],
                    'product_title' => $item['title'],
                    'variant_id' => $item['variant_id'],
                    'variant_name' => $item['variant_name'],
                    'product_img'   => $item['image'],
                    'product_type'  => $item['type'],
                    'product_price' => $item['price'],
                    'product_qty'   => $item['qty'],
                    'total_amount'   => $item['qty'] * $item['price'],
                ];

                $carts->push($cartItem);
                $grandTotal += ($item['price'] * $item['qty']);
            }
        }

        // echo '<pre>';
        // print_r($carts->toArray());
        // echo '</pre>';
        // exit;

        return view('checkout', compact('carts', 'grandTotal', 'countrys', 'address'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'product_id' => 'required',
            'product_type' => 'required',
            'quantity'   => 'required|integer|min:1',
        ]);

        $type = $request->product_type;
        try {
            $realProductId = decrypt($request->product_id);
            $variant_id = decrypt($request->variant_id) ?? null;
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid product data.'], 400);
        }

        $authUser = Auth::id();

        if ($authUser) {
            $updated = Cart::query()
                ->where('user_id', $authUser)
                ->where('product_id', $realProductId)
                ->where('product_type', $type)
                ->when(!empty($variant_id), function($q) use ($variant_id) {
                    $q->where('variant_id', $variant_id);
                })
                ->first();

            if ($updated) {
                $updated->update([
                    'product_qty' => $request->quantity,
                    'total_amount' => $request->quantity * $updated->product_price
                ]);
            }

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully.',
                ], 200);
            }

            return response()->json(['error' => 'Item not found in cart.'], 404);

        } else {
            $cart = session()->get('cart', []);

            $cartKey = $type . '_' . $realProductId . (!empty($variant_id) ? '_' . $variant_id : '');

            if (isset($cart[$cartKey])) {

                $cart[$cartKey]['qty'] = $request->quantity;

                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'Cart session updated successfully.',
                ], 200);
            }

            return response()->json(['error' => 'Item not found in session cart.'], 404);
        }
    }

    public function remove(Request $request)
    {

        $request->validate([
            'product_id' => 'required',
            'product_type' => 'required',
        ]);

        $type = $request->product_type;

        try {
            $realProductId = decrypt($request->product_id);
            $variant_id = decrypt($request->variant_id) ?? null;
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid product data.'], 400);
        }

        $authUser = Auth::id();

        if ($authUser) {
            $deleted = Cart::query()->where('user_id', $authUser)
                ->where('product_id', $realProductId)
                ->when(!empty($variant_id), function($q) use ($variant_id) {
                    $q->where('variant_id', $variant_id);
                })
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'cart'    => Cart::query()->where('user_id', $authUser)->count(),
                ], 200);
            }

            return response()->json(['error' => 'Item not found in cart.'], 404);

        } else {
            $cart = session()->get('cart', []);

            $cartKey = $type . '_' . $realProductId . (!empty($variant_id) ? '_' . $variant_id : '');

            if (isset($cart[$cartKey])) {

                unset($cart[$cartKey]);

                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from session cart.',
                    'cart'    => !empty($cart) ? count($cart) : 0,
                ], 200);
            }

            return response()->json(['error' => 'Item not found in session cart.'], 404);
        }
    }

    public function addToCart(Request $request)
    {

        $request->validate([
            'product_id'    => 'required|string',
            'product_type'  => 'required|string|max:100',
            'product_price' => 'nullable|numeric|min:0',
            'product_qty'   => 'required|integer|min:1',
        ]);

        try {
            $id = decrypt($request->product_id);
            $variant_id = $request->filled('variant_id') ? ($request->variant_id) : null;
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Invalid product.');
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Invalid product ID.'
            // ], 400);
        }

        $variantName = null;
        $variantID = null;
        $title = null;
        $img = null;
        $price = 0;

        $type  = $request->product_type;
        $reqQty = $request->product_qty ?? 1;
        $authUser = Auth::id();

        if ($type == 'product') {
            $dp = DigitalProduct::query()->find($id);
            $title = $dp->name ?? null;
            $img = $dp->image ?? null;
            $price = $dp->price ?? 0;

        } elseif ($type == 'service') {
            $ds = DigitalService::select(['id', 'name', 'image', 'price'])
                    ->with([
                        'variants' => function ($q) use ($variant_id) {
                            $q->when(!empty($variant_id), function ($sq) use ($variant_id) {
                                $sq->select(['id','service_id','name','price'])
                                ->where('id', $variant_id);
                            });
                        }
                    ])
                    ->find($id);

            $title = $ds->name ?? null;
            $img = $ds->image ?? null;
            $price = $ds->price ?? 0;

            if ($ds && $ds->variants && $ds->variants->isNotEmpty()) {
                $variant = $ds->variants->first();
                $variantID = $variant->id;
                $variantName = $variant->name;
                $price = $variant->price;
            }
        }

        $user_wallet = Auth::user()?->balance;

        $balance = $user_wallet?->balance ?? 0;

        $approvel = $user_wallet?->is_approved;

        if ($approvel == 0) {
            // return redirect()->back()->with('warning', 'your wallet balance request are pending, please wait for admin approval!');
        } else if ($approvel == 2) {
            // return redirect()->back()->with('error', 'your wallet balance request are rejected, please contact for admin.');
        }

        $total_price = $reqQty * $price;

        if ($balance < $total_price) {
            // return redirect()->back()->with('error', 'your wallet balance is low please add balance in your wallet!');
        }

        try {
            if ($authUser) {
                $existingCartItem = Cart::query()->where('user_id', $authUser)
                    ->where('product_id', $id)
                    ->where('product_type', $type)
                    ->when($variantID, function($q) use ($variantID) {
                        return $q->where('variant_id', $variantID);
                    })
                    ->first();

                if ($existingCartItem) {
                    if ($existingCartItem->product_type == 'product') {
                        $newQty = $existingCartItem->product_qty + $reqQty;
                    } else if ($existingCartItem->product_type == 'service') {
                        $newQty = $reqQty;
                    }

                    $existingCartItem->update([
                        'product_qty'   => $newQty,
                        'product_price' => $price,
                        'total_amount'  => $newQty * $price
                    ]);
                } else {
                    $initialQty = $reqQty;

                    Cart::create([
                        'user_id'       => $authUser,
                        'product_id'    => $id,
                        'variant_id'    => $variantID,
                        'variant_name'    => $variantName,
                        'product_title' => $title,
                        'product_img'   => $img,
                        'product_type'  => $type,
                        'product_price' => $price,
                        'product_qty'   => $initialQty,
                        'total_amount'  => $initialQty * $price
                    ]);
                }

                $cartCount = Cart::query()->where('user_id', $authUser)->count();

            } else {
                $cart = session()->get('cart', []);
                // session()->forget('cart');

                $cartKey = $type . '_' . $id . ($variantID ? '_' . $variantID : '');

                if (isset($cart[$cartKey])) {
                    if ($cart[$cartKey]['type'] == 'product') {
                        $cart[$cartKey]['qty'] = $cart[$cartKey]['qty'] + $reqQty;
                    } else if ($cart[$cartKey]['type'] == 'service') {
                        $cart[$cartKey]['qty'] = $reqQty;
                    }
                } else {
                    $cart[$cartKey] = [
                        'id'           => $id,
                        'title'        => $title,
                        'image'        => $img,
                        'type'         => $type,
                        'price'        => $price,
                        'qty'          => $reqQty,
                        'variant_id'   => $variantID,
                        'variant_name' => $variantName,
                    ];
                }

                session()->put('cart', $cart);
                $cartCount = count($cart);
            }

            $success_message = '';
            if ($type == 'product') {
                $success_message = 'Product added to cart successfully!';
            } elseif ($type == 'service') {
                $success_message = 'Service added to cart successfully!';
            }

            return redirect()->back()->with('success', $success_message);

            // return response()->json([
            //     'status'  => true,
            //     'message' => $success_message,
            //     'cart'    => $cartCount
            // ]);

        } catch (\Exception $e) {
            Log::error('Add To Cart Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');

            // return response()->json([
            //     'status'  => false,
            //     'message' => 'Something went wrong.'
            // ], 500);
        }
    }

    public static function storeCartitems()
    {
        $authUser = Auth::id();
        $cartSession = session()->get('cart', []);

        if (empty($cartSession) || !$authUser) {
            return;
        }

        try {
            foreach ($cartSession as $key => $item) {

                $variantID = $item['variant_id'] ?? null;
                $variantName = $item['variant_name'] ?? null;

                $existingCartItem = Cart::query()->where('user_id', $authUser)
                    ->where('product_id', $item['id'])
                    ->where('product_type', $item['type'])
                    ->when($variantID, function($q) use ($variantID) {
                        $q->where('variant_id', $variantID);
                    })
                    ->first();

                $price = 0;
                if ($item['type'] == 'product') {
                    $dp = DigitalProduct::query()->find($item['id']);
                    $price = $dp->price ?? 0;

                } else if ($item['type'] == 'service') {
                    $ds = DigitalService::query()->when(!empty($variantID), function ($query) use ($variantID) {
                            $query->with(['variants' => function ($q) use ($variantID) {
                                $q->where('id', $variantID);
                            }]);
                        })
                        ->find($item['id']);

                    if ($ds) {
                        if (!empty($variantID) && $ds->relationLoaded('variants') && $ds->variants->isNotEmpty()) {
                            $price = $ds->variants->first()->price;
                        } else {
                            $price = $ds->price ?? 0;
                        }
                    }
                }

                if (!empty($existingCartItem)) {
                    $newQty = $existingCartItem->product_qty + $item['qty'];

                    $existingCartItem->update([
                        'product_qty'   => $newQty,
                        'product_price' => $price,
                        'total_amount'  => $newQty * $price
                    ]);
                } else {
                    $initialQty = $item['qty'];

                    Cart::create([
                        'user_id'       => $authUser,
                        'product_id'    => $item['id'],
                        'variant_id'    => $variantID,
                        'variant_name'  => $variantName,
                        'product_title' => $item['title'],
                        'product_img'   => $item['image'],
                        'product_type'  => $item['type'],
                        'product_price' => $price,
                        'product_qty'   => $initialQty,
                        'total_amount'  => $initialQty * $price,
                    ]);
                }
            }

            session()->forget('cart');

        } catch (\Exception $e) {
            Log::error('Error storeCartitems() -> ' . $e->getMessage());
        }
    }
}

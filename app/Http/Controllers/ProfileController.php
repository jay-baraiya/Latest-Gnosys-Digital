<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Country;
use App\Models\Order;
use App\Models\State;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $orders = Order::query()->where('user_id', auth()->id())->paginate(5)->fragment('orders');

        $tickets = Order::query()->with(['tickets:id,ticket_number,datetime,order_id,user_id,developer_id,order_item_id,status,cancelled_by,cancel_reason','user:id,name','orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price,total_amount','tickets.orderItems:id,order_id,product_id,product_name,product_type,variant_id,variant_name,product_price'])->select(['id','user_id','order_number','date_time'])->where('user_id',Auth::id())->paginate(1)->fragment('ticket');

        $countries = Country::all();
        $states = State::all();

        return view('profile.edit', [
            'user' => $request->user(),
            'orders' => $orders,
            'tickets' => $tickets,
            'countries' => $countries,
            'states' => $states,
        ]);
    }

    public function updateUserInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'      => ['required', 'string', 'max:20'],
            'zip'        => ['nullable', 'string', 'max:15'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'state_id'   => ['required', 'integer', 'exists:states,id'],
            'city_id'    => ['required', 'string'],
            'address'    => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $user->update($validated);

            return redirect()->back()
                ->withFragment('profile')
                ->with('success', 'Profile info updated successfully.');

        } catch (\Exception $e) {
            Log::error('User Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->withFragment('profile')
                ->with('error', 'Something went wrong while updating your profile. Please try again later.');
        }
    }

    public function getStates($country_id)
    {
        $states = State::query()
                    ->where('country_id', $country_id)
                    ->select('id', 'name')
                    ->orderBy('name', 'asc')
                    ->get();

        return response()->json($states);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

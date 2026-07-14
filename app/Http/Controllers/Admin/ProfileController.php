<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    protected $moduleName = 'Profile';
    protected $moduleUrl = 'admin.profile.edit';

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
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $roles = \App\Models\Role::query()->where('status', 1)->get();
        $designations = \App\Models\Designation::query()->where('status', 1)->get();
        
        $tab = $request->query('tab', 'profile-information');

        return view('admin.profile.edit', [
            'user' => $request->user(),
            'roles' => $roles,
            'designations' => $designations,
            'tab' => $tab,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($request->user()->id),
            ],
            'phone' => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('users')->ignore($request->user()->id)],
            'zip' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'designation_id' => ['required'],
            'country_id' => ['required'],
            'state_id' => ['required'],
            'city_id' => ['required'],
        ]);

        $user = $request->user();
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (array_key_exists('phone', $validated)) {
            $user->phone = $validated['phone'];
        }
        if (array_key_exists('zip', $validated)) {
            $user->zip = $validated['zip'];
        }
        if (array_key_exists('address', $validated)) {
            $user->address = $validated['address'];
        }
        $user->designation_id = $validated['designation_id'];
        $user->country_id = $validated['country_id'];
        $user->state_id = $validated['state_id'];
        $user->city_id = $validated['city_id'];

        if ($request->hasFile('image')) {
            if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                $pathToDelete = str_replace(['storage/', '/storage/'], '', $user->image);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($pathToDelete)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($pathToDelete);
                }
            }
            $path = $request->file('image')->store('profiles', 'public');
            $user->image = 'storage/' . $path;
        } elseif ($request->input('remove_image') == '1') {
            if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                $pathToDelete = str_replace(['storage/', '/storage/'], '', $user->image);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($pathToDelete)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($pathToDelete);
                }
            }
            $user->image = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::to(route('admin.profile.edit') . '?tab=profile-information')->with('success', 'Profile updated successfully.');
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

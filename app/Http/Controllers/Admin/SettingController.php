<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    protected $moduleName = 'Settings';
    protected $moduleUrl = 'admin.settings.index';

    public function __construct()
    {
        view()->share([
            'moduleName' => $this->moduleName,
            'moduleUrl' => $this->moduleUrl,
        ]);
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'general-settings');

        $user = auth()->user();

        $roles = Role::query()->where('status', 1)->get();

        $designations = Designation::query()->where('status', 1)->get();

        $settings = Setting::query()->first();

        return view('admin.setting.index', compact('user','tab','roles','designations','settings'));
    }

    public function update(Request $request, string $setting)
    {
        $tab = $request->query('tab', 'general-settings');
        try {
            $userId = decrypt($setting);
            $user = User::findOrFail($userId);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
                'password' => 'nullable|string|min:8|confirmed',
                'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
                'address' => 'nullable|string',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'zip' => 'nullable|string|max:10',
                // 'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:1024',
            ],[
                'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
                'image.max' => 'The image size must not exceed 1 MB.',
            ]);

            $imagePath = $user->image;

            if ($request->remove_existing_image == '1') {
                if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $user->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }
                $imagePath = null;
            }

            if ($request->hasFile('image')) {

                $validatedData = $request->validate([
                    'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:1024',
                ],[
                    'image.mimes' => 'Only JPEG, JPG, PNG, and WEBP images are allowed.',
                    'image.max' => 'The image size must not exceed 1 MB.',
                ]);

                if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                    $pathToDelete = str_replace('/storage/', '', $user->image);
                    if(Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }
                }

                $path = $request->file('image')->store('users', 'public');
                $imagePath = Storage::url($path);

            }

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $validatedData['image'] = $imagePath;

            $user->update($validatedData);

            return redirect()->route('admin.settings.index', ['tab' => $request->query('tab', $tab)])
                 ->withInput()
                 ->with('success', 'User setting updated successfully.');

            // return redirect(url()->previous() . '?' . http_build_query($request->query()))->with('success', 'User setting updated successfully.');
        } catch (\Exception $e) {
            Log::error('User Setting Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            return redirect()->route('admin.settings.index', ['tab' => $request->query('tab', $tab)])
                 ->withInput()
                 ->with('error', 'Failed to update settings. Please try again later.');

            // return redirect(url()->previous() . '?' . http_build_query($request->query()))->withInput()->with('error', 'Failed to update user. Please try again later.');
        }
    }

    public function store(Request $request)
    {
        $tab = $request->input('tab', 'theme-settings');

        try {
            $setting = Setting::query()->first() ?? new Setting();

            if ($tab === 'theme-settings') {
                $setting->theme_mode = $request->theme_mode;
                $setting->layout_size = $request->layout_size;
                $setting->sidebar_color = $request->sidebar_color;
                $setting->topbar_color = $request->topbar_color;
                $setting->primary_color = $request->primary_color;
            } elseif ($tab === 'email-setting' || $tab === 'email-settings') {
                $setting->mail_mailer = $request->mail_mailer;
                $setting->mail_host = $request->mail_host;
                $setting->mail_port = $request->mail_port;
                $setting->mail_username = $request->mail_username;
                $setting->mail_password = $request->mail_password;
                $setting->mail_encryption = $request->mail_encryption;
                $setting->mail_from_address = $request->mail_from_address;
                $setting->mail_from_name = $request->mail_from_name;
                
                $setting->imap_host = $request->imap_host;
                $setting->imap_protocol = $request->imap_protocol;
                $setting->imap_port = $request->imap_port;
                $setting->imap_encryption = $request->imap_encryption;
                $setting->imap_username = $request->imap_username;
                $setting->imap_password = $request->imap_password;
            } elseif ($tab === 'paypal-settings') {
                $setting->paypal_mode = $request->paypal_mode;
                $setting->paypal_client_id = $request->paypal_client_id;
                $setting->paypal_client_secret = $request->paypal_client_secret;
                $setting->paypal_currency = $request->paypal_currency;
                $setting->paypal_webhook_url = $request->paypal_webhook_url;
                $setting->paypal_return_url = $request->paypal_return_url;
                $setting->paypal_cancel_url = $request->paypal_cancel_url;
                $setting->paypal_enable_for_credits = $request->has('paypal_enable_for_credits') ? 1 : 0;
                $setting->paypal_enable_for_events = $request->has('paypal_enable_for_events') ? 1 : 0;
            }

            $setting->save();
            Cache::forget('app_settings');

            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->with('success', 'Settings updated successfully.');

        } catch (\Exception $e) {
            Log::error('Settings Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->withInput()
                ->with('error', 'Failed to update settings. Please try again later.');
        }
    }

    public function storeWebsiteSetting(Request $request)
    {

        $tab = $request->input('tab', 'website-settings');

        try {
            $setting = Setting::query()->first() ?? new Setting();

            $imageFields = [
                'favicon',
                'header_logo',
                'footer_logo',
                'mobile_header_logo',
                'mobile_footer_logo'
            ];

            foreach ($imageFields as $field) {
                $removeFlag = 'remove_' . $field;

                if ($request->$removeFlag == '1') {
                    if ($setting->$field) {
                        $pathToDelete = str_replace('/storage/', '', $setting->$field);
                        if (Storage::disk('public')->exists($pathToDelete)) {
                            Storage::disk('public')->delete($pathToDelete);
                        }
                    }
                    $setting->$field = null;
                }

                if ($request->hasFile($field)) {
                    if ($setting->$field) {
                        $pathToDelete = str_replace('/storage/', '', $setting->$field);
                        if (Storage::disk('public')->exists($pathToDelete)) {
                            Storage::disk('public')->delete($pathToDelete);
                        }
                    }

                    $path = $request->file($field)->store('settings', 'public');
                    $setting->$field = Storage::url($path);
                }
            }

            $setting->site_name = $request->site_name;
            $setting->contact_email = $request->contact_email;
            $setting->contact_phone = $request->contact_phone;
            $setting->address_one = $request->address_one;
            $setting->address_two = $request->address_two;
            $setting->meta_title = $request->meta_title;
            $setting->meta_keywords = $request->meta_keywords;
            $setting->meta_description = $request->meta_description;
            $setting->facebook_url = $request->facebook_url;
            $setting->instagram_url = $request->instagram_url;
            $setting->twitter_url = $request->twitter_url;
            $setting->linkedin_url = $request->linkedin_url;
            $setting->pinterest_url = $request->pinterest_url;

            $setting->save();

            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->with('success', 'Website setting updated successfully.');

        } catch (\Exception $e) {
            // એરર આવે તો લોગ ફાઇલમાં સેવ કરો
            Log::error('Website Setting Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('admin.settings.index', ['tab' => $tab])
                ->withInput()
                ->with('error', 'Failed to update website settings. Please try again later.');
        }
    }

    public function show()
    {
        echo '<pre>';
        print_r('test');
        echo '</pre>';
        exit;
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            Mail::raw('This is a test email to verify your SMTP settings in the Gnosys Digital ERP system. If you received this, your email configuration is working perfectly!', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email - SMTP Configuration Verification');
            });

            return redirect()->route('admin.settings.index', ['tab' => 'email-settings'])
                ->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            Log::error('Test Email Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('admin.settings.index', ['tab' => 'email-settings'])
                ->with('error', 'Failed to send test email. Error: ' . $e->getMessage());
        }
    }

    public function testPaypalConnection(Request $request)
    {
        $request->validate([
            'paypal_mode' => 'required',
            'paypal_client_id' => 'required',
            'paypal_client_secret' => 'required',
        ]);

        $mode = $request->paypal_mode;
        $clientId = $request->paypal_client_id;
        $secret = $request->paypal_client_secret;

        $url = $mode === 'live' ? 'https://api-m.paypal.com/v1/oauth2/token' : 'https://api-m.sandbox.paypal.com/v1/oauth2/token';

        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post($url, [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                return redirect()->route('admin.settings.index', ['tab' => 'paypal-settings'])
                    ->with('success', 'PayPal connection successful! Credentials are valid.');
            }

            return redirect()->route('admin.settings.index', ['tab' => 'paypal-settings'])
                ->with('error', 'PayPal connection failed. Invalid credentials. Response: ' . $response->json('error_description', 'Unknown error'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PayPal Test Connection Error', [
                'message' => $e->getMessage(),
            ]);
            return redirect()->route('admin.settings.index', ['tab' => 'paypal-settings'])
                ->with('error', 'Failed to test PayPal connection. Error: ' . $e->getMessage());
        }
    }
}

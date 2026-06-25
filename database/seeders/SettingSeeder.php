<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'site_name' => 'Gnosys Digital',
            'contact_email' => 'connect@gnosysdigital.com',
            'contact_phone' => '+1 647 947 9546',
            'address_one' => '1664, 225 The East Mall, Toronto, ON, M9B 0A9.',
            'address_two' => '20-22 Wenlock Road, London N1 7GU, United Kingdom.',
            'meta_title' => 'Gnosys Digital — Expert-Built Digital Services & Products, 100% In-House',
            'meta_keywords' => 'Gnosys Digital',
            'meta_description' => 'Discover Gnosys Digital — a global studio offering expert-built digital services, templates, and tools. 100% in-house, no freelancers, just results.',
            'facebook_url' => 'https://www.facebook.com/gnosys.digital/',
            'instagram_url' => 'https://www.instagram.com/gnosysdigital/',
            'twitter_url' => 'https://x.com/GnosysDigital',
            'linkedin_url' => 'https://www.linkedin.com/company/gnosys-digital/',
            'pinterest_url' => 'https://in.pinterest.com/gnosysdigital/',
        ];

        Setting::create($data);
    }
}

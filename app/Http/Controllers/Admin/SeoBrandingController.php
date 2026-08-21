<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoBrandingController extends Controller
{
    /**
     * Display SEO & Branding management form for Super Admin.
     */
    public function edit()
    {
        $settings = [
            'site_name' => SystemSetting::getByKey('site_name', 'TalentFlow'),
            'site_tagline' => SystemSetting::getByKey('site_tagline', 'Platform Rekrutmen Enterprise & Portal Karir Modern'),
            'site_logo' => SystemSetting::getByKey('site_logo'),
            'site_favicon' => SystemSetting::getByKey('site_favicon'),
            'seo_meta_title' => SystemSetting::getByKey('seo_meta_title', 'TalentFlow - Solusi Rekrutmen Enterprise & Karir Impian'),
            'seo_meta_description' => SystemSetting::getByKey('seo_meta_description', 'Temukan lowongan kerja terbaik dan kelola rekrutmen perusahaan secara efisien dengan TalentFlow Enterprise.'),
            'seo_meta_keywords' => SystemSetting::getByKey('seo_meta_keywords', 'lowongan kerja, karir, rekrutmen, hr management, lamar kerja, cv builder, psikotes online'),
            'seo_og_image' => SystemSetting::getByKey('seo_og_image'),
            'google_analytics_id' => SystemSetting::getByKey('google_analytics_id'),
            'support_email' => SystemSetting::getByKey('support_email', 'support@talentflow.id'),
            'support_phone' => SystemSetting::getByKey('support_phone', '+62 812-3456-7890'),
            'address' => SystemSetting::getByKey('address', 'Jakarta South Quarter, Lt. 15, Jakarta Selatan'),
        ];

        return view('admin.settings.seo_branding', compact('settings'));
    }

    /**
     * Update Site Branding, Favicon, Logo & SEO Settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'seo_meta_title' => 'required|string|max:255',
            'seo_meta_description' => 'required|string|max:500',
            'seo_meta_keywords' => 'nullable|string|max:500',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:100',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,svg,webp|max:1024',
            'seo_og_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
        ]);

        // Save Text Fields
        $fields = [
            'site_name', 'site_tagline', 'seo_meta_title', 'seo_meta_description',
            'seo_meta_keywords', 'support_email', 'support_phone', 'address', 'google_analytics_id'
        ];

        foreach ($fields as $field) {
            SystemSetting::setKey($field, $request->input($field));
        }

        // Handle File Uploads
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('branding', 'public');
            SystemSetting::setKey('site_logo', $path);
        }

        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('branding', 'public');
            SystemSetting::setKey('site_favicon', $path);
        }

        if ($request->hasFile('seo_og_image')) {
            $path = $request->file('seo_og_image')->store('branding', 'public');
            SystemSetting::setKey('seo_og_image', $path);
        }

        AuditLog::record('seo_branding_updated', "Super Admin memperbarui konfigurasi Branding, Logo, Favicon, dan SEO Web");

        return back()->with('success', 'Pengaturan Branding, Logo, Favicon, dan SEO Web berhasil diperbarui!');
    }
}

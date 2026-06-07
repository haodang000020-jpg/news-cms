<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|max:255',
            'agency_name' => 'nullable|max:255',
            'site_subtitle' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:50',
            'address' => 'nullable|max:500',
            'footer_text' => 'nullable',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
           'header_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        $fields = [
            'site_name',
            'agency_name',
            'site_subtitle',
            'email',
            'phone',
            'address',
            'footer_text',
        ];

        foreach ($fields as $field) {
            Setting::setValue($field, $request->input($field));
        }

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::getValue('logo');

            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $logoPath = $request->file('logo')->store('settings', 'public');

            Setting::setValue('logo', $logoPath);
        }

       if ($request->hasFile('header_banner')) {
    $oldBanner = Setting::getValue('header_banner');

    if ($oldBanner) {
        Storage::disk('public')->delete($oldBanner);
    }

    $bannerPath = $request
        ->file('header_banner')
        ->store('settings', 'public');

    Setting::setValue('header_banner', $bannerPath);
}

        return back()->with('success', 'Cập nhật thông tin website thành công');
    }
}
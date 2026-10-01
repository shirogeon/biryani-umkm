<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = StoreSetting::all()->pluck('value', 'key');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'settings' => $settings,
            ]);
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'store_name' => 'required|string|max:150',
            'store_tagline' => 'nullable|string|max:255',
            'store_phone' => 'required|string|max:20',
            'store_address' => 'required|string|max:300',
            'store_open_hours' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:100',
            'qris_merchant_name' => 'nullable|string|max:100',
            'shipping_flat_rate' => 'required|numeric|min:0',
        ];

        $validated = $request->validate($keys);

        foreach ($validated as $key => $val) {
            $group = match ($key) {
                'store_phone', 'store_address' => 'contact',
                'bank_name', 'bank_account_number', 'bank_account_holder', 'qris_merchant_name' => 'payment',
                'shipping_flat_rate' => 'delivery',
                default => 'general',
            };

            StoreSetting::set($key, (string) $val, $group);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengaturan toko berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}

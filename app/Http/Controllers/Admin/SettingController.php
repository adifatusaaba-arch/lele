<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::orderBy('group')->orderBy('id')->get();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($data['settings'] as $key => $value) {
            Setting::setValue($key, $value);
        }

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}

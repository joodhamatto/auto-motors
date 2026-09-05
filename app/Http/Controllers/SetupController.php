<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSetupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SetupController extends Controller
{
    public function create(): View
    {
        return view('setup');
    }

    public function store(StoreSetupRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $admin = DB::transaction(function () use ($data) {
            if (User::query()->where('is_admin', true)->lockForUpdate()->exists()) {
                return null;
            }

            $admin = User::create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['password']),
                'is_admin' => true,
            ]);

            return $admin;
        });

        if (! $admin) {
            return redirect()->route('login');
        }

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Administrator account created. Please sign in.');
    }
}

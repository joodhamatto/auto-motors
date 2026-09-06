<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Service;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                'services' => Service::count(),
                'products' => Product::count(),
                'vehicles' => Vehicle::count(),
                'faqs' => Faq::count(),
            ],
        ]);
    }
}

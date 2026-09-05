<?php

namespace App\Http\Controllers;

use App\Models\Advantage;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $settings = SiteSetting::map();
        $services = Service::where('is_active', true)->orderBy('display_order')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('display_order')->get();
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($category) use ($request) {
                    $category->where('slug', $request->string('category'))
                        ->where('is_active', true);
                });
            })
            ->orderBy('display_order')
            ->paginate(8)
            ->withQueryString();
        $vehicleCategories = VehicleCategory::with([
            'vehicles' => fn ($query) => $query->where('is_active', true)->orderBy('display_order'),
        ])->where('is_active', true)->orderBy('display_order')->get();
        $advantages = Advantage::where('is_active', true)->orderBy('display_order')->get();
        $faqs = Faq::where('is_active', true)->orderBy('display_order')->get();

        return view('portfolio', compact('settings', 'services', 'categories', 'products', 'vehicleCategories', 'advantages', 'faqs'));
    }

    public function sitemap()
    {
        return response()->view('sitemap')->header('Content-Type', 'application/xml');
    }
}

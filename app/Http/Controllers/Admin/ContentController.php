<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    private array $resources = [
        'services' => [
            Service::class,
            'Services',
            ['title_fr', 'title_en'],
            [
                'title_fr' => 'text',
                'title_en' => 'text',
                'description_fr' => 'textarea',
                'description_en' => 'textarea',
                'icon' => 'text',
                'image' => 'image',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
        'categories' => [
            ProductCategory::class,
            'Product categories',
            ['name_fr', 'name_en'],
            [
                'name_fr' => 'text',
                'name_en' => 'text',
                'slug' => 'text',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
        'products' => [
            Product::class,
            'Products',
            ['name_fr', 'name_en'],
            [
                'product_category_id' => 'product_category',
                'name_fr' => 'text',
                'name_en' => 'text',
                'description_fr' => 'textarea',
                'description_en' => 'textarea',
                'price' => 'number',
                'image_reference' => 'text',
                'main_image' => 'image',
                'gallery' => 'gallery',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
        'vehicle-categories' => [
            VehicleCategory::class,
            'Vehicle categories',
            ['name_fr', 'name_en'],
            [
                'name_fr' => 'text',
                'name_en' => 'text',
                'slug' => 'text',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
        'vehicles' => [
            Vehicle::class,
            'Vehicles',
            ['name'],
            [
                'vehicle_category_id' => 'vehicle_category',
                'name' => 'text',
                'description_fr' => 'textarea',
                'description_en' => 'textarea',
                'image' => 'image',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
        'advantages' => [
            Advantage::class,
            'Advantages',
            ['title_fr', 'title_en'],
            [
                'title_fr' => 'text',
                'title_en' => 'text',
                'icon' => 'text',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
        'faqs' => [
            Faq::class,
            'FAQs',
            ['question_fr', 'question_en'],
            [
                'question_fr' => 'text',
                'question_en' => 'text',
                'answer_fr' => 'textarea',
                'answer_en' => 'textarea',
                'display_order' => 'number',
                'is_active' => 'checkbox',
            ],
        ],
    ];

    private function definition(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);

        return $this->resources[$resource];
    }

    public function index(Request $request, string $resource)
    {
        [$model, $title, $columns] = $this->definition($resource);
        $query = $model::query()->orderBy('display_order');
        if ($term = trim((string) $request->query('q'))) {
            $query->where(function ($query) use ($columns, $term) {
                foreach ($columns as $index => $column) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $query->{$method}($column, 'like', "%$term%");
                }
            });
        }

        return view('admin.content.index', [
            'items' => $query->paginate($resource === 'products' ? 12 : 25)->withQueryString(),
            'resource' => $resource,
            'title' => $title,
            'columns' => $columns,
        ]);
    }

    public function create(string $resource)
    {
        return $this->form($resource);
    }

    public function edit(string $resource, int $id)
    {
        [$model] = $this->definition($resource);

        return $this->form($resource, $model::findOrFail($id));
    }

    private function form(string $resource, $item = null)
    {
        [, $title,, $fields] = $this->definition($resource);

        return view('admin.content.form', compact('resource', 'title', 'fields', 'item') + [
            'productCategories' => ProductCategory::orderBy('display_order')->get(),
            'vehicleCategories' => VehicleCategory::orderBy('display_order')->get(),
        ]);
    }

    public function store(Request $request, string $resource)
    {
        return $this->persist($request, $resource);
    }

    public function update(Request $request, string $resource, int $id)
    {
        [$model] = $this->definition($resource);

        return $this->persist($request, $resource, $model::findOrFail($id));
    }

    private function persist(Request $request, string $resource, $item = null)
    {
        [$model,,, $fields] = $this->definition($resource);
        $rules = $this->rules($resource, $item?->id);
        $data = $request->validate($rules);
        $item ??= new $model;
        foreach (array_keys($fields) as $field) {
            if ($fields[$field] === 'checkbox') {
                $data[$field] = $request->boolean($field);
            }
            if ($fields[$field] === 'image' && $request->hasFile($field)) {
                $old = $item->{$field};
                $data[$field] = $request->file($field)->store('uploads/'.str_replace('-', '_', $resource), 'public');
                $this->deleteUpload($old);
            }
        }
        unset($data['gallery']);
        $item->fill($data)->save();
        if ($resource === 'products' && $request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                ProductImage::create([
                    'product_id' => $item->id,
                    'path' => $file->store('uploads/products/gallery', 'public'),
                    'display_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.content.index', $resource)->with('success', 'Saved successfully.');
    }

    private function rules(string $resource, ?int $id): array
    {
        $image = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:max_width=4000,max_height=4000'];
        $base = ['display_order' => ['required', 'integer', 'min:0', 'max:9999'], 'is_active' => ['nullable', 'boolean']];

        return $base + match ($resource) {
            'services' => [
                'title_fr' => ['required', 'string', 'max:150'],
                'title_en' => ['required', 'string', 'max:150'],
                'description_fr' => ['required', 'string', 'max:1500'],
                'description_en' => ['required', 'string', 'max:1500'],
                'icon' => ['required', 'regex:/^bi-[a-z0-9-]+$/'],
                'image' => $image,
            ],
            'categories' => [
                'name_fr' => ['required', 'string', 'max:100'],
                'name_en' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'alpha_dash', 'max:100', Rule::unique('product_categories')->ignore($id)],
            ],
            'products' => [
                'product_category_id' => ['required', 'exists:product_categories,id'],
                'name_fr' => ['required', 'string', 'max:150'],
                'name_en' => ['required', 'string', 'max:150'],
                'description_fr' => ['nullable', 'string', 'max:2000'],
                'description_en' => ['nullable', 'string', 'max:2000'],
                'price' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
                'image_reference' => ['nullable', 'string', 'max:80'],
                'main_image' => $image,
                'gallery' => ['nullable', 'array', 'max:8'],
                'gallery.*' => $image,
            ],
            'vehicle-categories' => [
                'name_fr' => ['required', 'string', 'max:100'],
                'name_en' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'alpha_dash', 'max:100', Rule::unique('vehicle_categories')->ignore($id)],
            ],
            'vehicles' => [
                'vehicle_category_id' => ['required', 'exists:vehicle_categories,id'],
                'name' => ['required', 'string', 'max:120'],
                'description_fr' => ['nullable', 'string', 'max:1500'],
                'description_en' => ['nullable', 'string', 'max:1500'],
                'image' => $image,
            ],
            'advantages' => [
                'title_fr' => ['required', 'string', 'max:150'],
                'title_en' => ['required', 'string', 'max:150'],
                'icon' => ['required', 'regex:/^bi-[a-z0-9-]+$/'],
            ],
            'faqs' => [
                'question_fr' => ['required', 'string', 'max:250'],
                'question_en' => ['required', 'string', 'max:250'],
                'answer_fr' => ['required', 'string', 'max:3000'],
                'answer_en' => ['required', 'string', 'max:3000'],
            ],
        };
    }

    public function destroy(string $resource, int $id)
    {
        [$model] = $this->definition($resource);
        $item = $model::findOrFail($id);
        if (($item instanceof ProductCategory && $item->products()->exists()) || ($item instanceof VehicleCategory && $item->vehicles()->exists())) {
            return back()->withErrors(['delete' => 'Move or delete linked items before deleting this category.']);
        }
        foreach (['image', 'main_image'] as $field) {
            $this->deleteUpload($item->{$field} ?? null);
        }
        if ($item instanceof Product) {
            foreach ($item->images as $image) {
                $this->deleteUpload($image->path);
            }
        }
        $item->delete();

        return back()->with('success', 'Deleted successfully.');
    }

    public function toggle(string $resource, int $id)
    {
        [$model] = $this->definition($resource);
        $item = $model::findOrFail($id);
        $item->update(['is_active' => ! $item->is_active]);

        return back()->with('success', 'Status updated.');
    }

    private function deleteUpload(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function settings()
    {
        return view('admin.settings', ['groups' => SiteSetting::orderBy('group')->orderBy('id')->get()->groupBy('group')]);
    }

    public function updateSettings(Request $request)
    {
        foreach (SiteSetting::all() as $setting) {
            if ($setting->type === 'image' && $request->hasFile($setting->key)) {
                $request->validate([
                    $setting->key => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:max_width=4000,max_height=4000'],
                ]);
                $old = $setting->value;
                $setting->value = $request->file($setting->key)->store('uploads/settings', 'public');
                $this->deleteUpload($old);
            } elseif (in_array($setting->type, ['localized_text', 'localized_textarea'], true)) {
                $validated = $request->validate([
                    $setting->key.'_fr' => ['required', 'string', 'max:3000'],
                    $setting->key.'_en' => ['required', 'string', 'max:3000'],
                ]);
                $setting->value_fr = $validated[$setting->key.'_fr'];
                $setting->value_en = $validated[$setting->key.'_en'];
            } else {
                $validated = $request->validate([$setting->key => [$setting->type === 'email' ? 'email' : 'nullable', 'string', 'max:3000']]);
                $setting->value = $validated[$setting->key] ?? null;
            }
            $setting->save();
        }

        return back()->with('success', 'Settings updated.');
    }

    public function destroyProductImage(Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 404);
        $this->deleteUpload($image->path);
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}

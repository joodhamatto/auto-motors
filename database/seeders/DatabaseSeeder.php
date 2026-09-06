<?php

namespace Database\Seeders;

use App\Models\Advantage;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $settings = [
            ['company_name', 'identity', 'Company name', null, null, 'AUTO MOTORS SARL', 'text'],
            ['logo', 'identity', 'Logo', null, null, 'images/auto-motors-logo.png', 'image'],
            ['hero_image', 'hero', 'Hero image', null, null, 'images/hero-automotive.png', 'image'],
            [
                'hero_title',
                'hero',
                'Hero title',
                'Votre partenaire automobile de confiance',
                'Your trusted automotive partner',
                null,
                'localized_text',
            ],
            [
                'hero_subtitle',
                'hero',
                'Hero subtitle',
                'Pièces automobiles, pneus, batteries et lubrifiants de qualité à des prix compétitifs.',
                'Quality automotive parts, tires, batteries and lubricants at competitive prices.',
                null,
                'localized_textarea',
            ],
            [
                'about',
                'about',
                'About',
                'AUTO MOTORS SARL est spécialisée dans la vente de pièces automobiles, batteries, lubrifiants, pneus et pièces détachées. Nous proposons des produits de qualité et un service fiable.',
                'AUTO MOTORS SARL specializes in automotive spare parts, batteries, lubricants, tires and vehicle components. We provide quality products and reliable service.',
                null,
                'localized_textarea',
            ],
            [
                'mission',
                'about',
                'Mission',
                'Fournir des pièces de qualité à des prix compétitifs.',
                'To provide quality parts at competitive prices.',
                null,
                'localized_textarea',
            ],
            [
                'delivery',
                'contact',
                'Delivery',
                'Nous proposons également la livraison selon les besoins de nos clients.',
                'We also provide delivery according to our customers’ needs.',
                null,
                'localized_textarea',
            ],
            [
                'address',
                'contact',
                'Address',
                'San Pedro, GAR, quartier SOTREF, en face de SACC Cacao.',
                'San Pedro, GAR, SOTREF district, opposite SACC Cacao.',
                null,
                'localized_textarea',
            ],
            ['phones', 'contact', 'Phone numbers', null, null, "0749616161\n0778969396\n0708236417", 'textarea'],
            ['email', 'contact', 'Email', null, null, 'Motorsauto166@gmail.com', 'email'],
            ['whatsapp', 'contact', 'WhatsApp number', null, null, '225708236417', 'text'],
            [
                'seo_title',
                'seo',
                'SEO title',
                'AUTO MOTORS SARL | Pièces automobiles en Côte d’Ivoire',
                'AUTO MOTORS SARL | Automotive Parts in Côte d’Ivoire',
                null,
                'localized_text',
            ],
            [
                'seo_description',
                'seo',
                'SEO description',
                'Pièces automobiles, batteries, lubrifiants et pneus de qualité à San Pedro, avec distribution en Côte d’Ivoire.',
                'Quality automotive parts, batteries, lubricants and tires in San Pedro, with distribution across Côte d’Ivoire.',
                null,
                'localized_textarea',
            ],
        ];
        foreach ($settings as [$key,$group,$label,$fr,$en,$value,$type]) {
            SiteSetting::updateOrCreate(['key' => $key], compact('group', 'label', 'value') + ['value_fr' => $fr, 'value_en' => $en, 'type' => $type]);
        }

        $services = [
            [
                'Importation de pièces automobiles',
                'Automotive parts import',
                'Importation de pièces et équipements automobiles de qualité.',
                'Importation of quality automotive parts and equipment.',
                'bi-box-seam',
            ],
            [
                'Pneus pour véhicules et camions',
                'Tires for vehicles and trucks',
                'Fourniture de pneus adaptés aux différents types de véhicules.',
                'Supply of tires suited to different types of vehicles.',
                'bi-circle',
            ],
            [
                'Batteries automobiles',
                'Automotive batteries',
                'Vente de batteries fiables pour voitures et véhicules professionnels.',
                'Reliable batteries for cars and professional vehicles.',
                'bi-battery-charging',
            ],
            [
                'Lubrifiants et huiles moteur',
                'Lubricants and motor oils',
                'Distribution de lubrifiants et huiles moteur TOTAL.',
                'Distribution of TOTAL lubricants and motor oils.',
                'bi-droplet-half',
            ],
            [
                'Vente en gros',
                'Wholesale',
                'Fourniture de produits automobiles aux magasins, revendeurs et professionnels.',
                'Automotive products supplied to stores, resellers and professionals.',
                'bi-boxes',
            ],
            [
                'Distribution en Côte d’Ivoire',
                'Distribution in Côte d’Ivoire',
                'Distribution de nos produits dans différentes régions de la Côte d’Ivoire.',
                'Distribution of our products across different regions of Côte d’Ivoire.',
                'bi-truck',
            ],
        ];
        foreach ($services as $i => $s) {
            Service::updateOrCreate(['title_fr' => $s[0]], [
                'title_en' => $s[1],
                'description_fr' => $s[2],
                'description_en' => $s[3],
                'icon' => $s[4],
                'display_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        foreach ([
            ['Batteries', 'Batteries', 'batteries'],
            ['Lubrifiants', 'Lubricants', 'lubricants'],
            ['Pneus', 'Tires', 'tires'],
            ['Pièces détachées', 'Spare Parts', 'spare-parts'],
        ] as $i => $c) {
            ProductCategory::updateOrCreate(['slug' => $c[2]], ['name_fr' => $c[0], 'name_en' => $c[1], 'display_order' => $i + 1, 'is_active' => true]);
        }
        $categories = [];
        foreach ([['Camionnettes', 'Light trucks', 'light-trucks'], ['Poids lourds', 'Heavy trucks', 'heavy-trucks']] as $i => $c) {
            $categories[$c[2]] = VehicleCategory::updateOrCreate(['slug' => $c[2]], ['name_fr' => $c[0], 'name_en' => $c[1], 'display_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([
            ['light-trucks', 'Kia'],
            ['light-trucks', 'Hyundai'],
            ['light-trucks', 'Canter'],
            ['heavy-trucks', 'Mercedes'],
            ['heavy-trucks', 'Sinotruk'],
            ['heavy-trucks', 'DAF'],
            ['heavy-trucks', 'Renault'],
        ] as $i => $v) {
            Vehicle::updateOrCreate(['vehicle_category_id' => $categories[$v[0]]->id, 'name' => $v[1]], ['display_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([
            ['Produits de qualité', 'Quality products', 'bi-patch-check'],
            ['Prix compétitifs', 'Competitive prices', 'bi-tags'],
            ['Importation fiable', 'Reliable importation', 'bi-shield-check'],
            ['Large gamme de produits', 'Wide product range', 'bi-grid'],
            ['Distribution partout en Côte d’Ivoire', 'Distribution throughout Côte d’Ivoire', 'bi-geo-alt'],
        ] as $i => $a) {
            Advantage::updateOrCreate(['title_fr' => $a[0]], ['title_en' => $a[1], 'icon' => $a[2], 'display_order' => $i + 1, 'is_active' => true]);
        }

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            User::updateOrCreate(['email' => env('ADMIN_EMAIL')], ['name' => env('ADMIN_NAME', 'Administrator'), 'password' => env('ADMIN_PASSWORD'), 'is_admin' => true]);
        }
    }
}

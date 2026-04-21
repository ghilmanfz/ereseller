<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PaymentAccount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = collect([
            'Skincare',
            'Bodycare',
            'Herbal',
            'Cosmetic',
            'Health',
        ])->mapWithKeys(function (string $name) {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            return [$name => $category];
        });

        $products = [
            ['name' => 'Acne Care Facial Wash', 'category' => 'Skincare', 'price' => 65000, 'rating' => 4.8, 'stock' => 24, 'slug' => 'acne-care-facial-wash', 'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=500&fit=crop', 'description' => 'Pembersih wajah untuk kulit berjerawat.'],
            ['name' => 'Sunblock Cream SR12', 'category' => 'Skincare', 'price' => 85000, 'rating' => 4.9, 'stock' => 15, 'slug' => 'sunblock-cream-sr12', 'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=400&h=500&fit=crop', 'description' => 'Perlindungan UV dengan bahan alami.'],
            ['name' => 'Deodorant Spray Premium', 'category' => 'Bodycare', 'price' => 40000, 'rating' => 4.6, 'stock' => 50, 'slug' => 'deodorant-spray-premium', 'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=500&fit=crop', 'description' => 'Menghilangkan bau badan hingga 24 jam.'],
            ['name' => 'Kopi Radix SR12', 'category' => 'Health', 'price' => 120000, 'rating' => 4.8, 'stock' => 30, 'slug' => 'kopi-radix-sr12', 'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=500&fit=crop', 'description' => 'Kopi herbal untuk stamina harian.'],
            ['name' => 'Lip Care Natural', 'category' => 'Cosmetic', 'price' => 25000, 'rating' => 4.5, 'stock' => 12, 'slug' => 'lip-care-natural', 'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=500&fit=crop', 'description' => 'Pelembab bibir alami.'],
            ['name' => 'Minyak Bulus SR12', 'category' => 'Herbal', 'price' => 95000, 'rating' => 4.7, 'stock' => 8, 'slug' => 'minyak-bulus-sr12', 'image' => 'https://images.unsplash.com/photo-1617897903246-719242758050?w=400&h=500&fit=crop', 'description' => 'Minyak tradisional multifungsi.'],
        ];

        foreach ($products as $item) {
            Product::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categories[$item['category']]->id,
                    'name' => $item['name'],
                    'image_url' => $item['image'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'compare_price' => $item['price'] + 10000,
                    'rating' => $item['rating'],
                    'stock' => $item['stock'],
                    'is_active' => true,
                ]
            );
        }

        PaymentAccount::query()->updateOrCreate(
            ['account_number' => '8420-112-998'],
            [
                'bank_name' => 'BANK CENTRAL ASIA (BCA)',
                'account_holder' => 'a.n. SINTIA SR12 DISTRIBUTOR',
                'badge_color' => 'bg-blue-600',
                'is_active' => true,
            ]
        );

        PaymentAccount::query()->updateOrCreate(
            ['account_number' => '133-00-1234567-8'],
            [
                'bank_name' => 'BANK MANDIRI',
                'account_holder' => 'a.n. SINTIA SR12 DISTRIBUTOR',
                'badge_color' => 'bg-yellow-500',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['whatsapp' => '081111111111'],
            [
                'name' => 'Admin Sintia',
                'address' => 'Parungpanjang, Bogor',
                'email' => 'admin@sr12.local',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::query()->updateOrCreate(
            ['whatsapp' => '082222222222'],
            [
                'name' => 'Customer Demo',
                'address' => 'Parungpanjang, Bogor',
                'email' => 'customer@sr12.local',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );
    }
}

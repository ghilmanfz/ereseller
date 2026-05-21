<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function landing(): View
    {
        $products = $this->featuredProducts();

        return view('pages.landing', [
            'landingSettings' => $this->landingSettings(),
            'products' => $products->map(fn (Product $product) => $this->mapProductCard($product))->all(),
        ]);
    }

    public function catalog(Request $request): View
    {
        $query = Product::query()->with('category')->where('is_active', true);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->string('q').'%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        $sort = $request->string('sort', 'latest')->toString();
        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('pages.catalog', [
            'allProducts' => $products->getCollection()->map(fn (Product $product) => $this->mapProductCard($product))->all(),
            'productTotal' => $products->total(),
            'categories' => Category::query()->orderBy('name')->get(),
            'currentCategory' => $request->string('category')->toString(),
            'currentQuery' => $request->string('q')->toString(),
            'currentSort' => $sort,
            'pagination' => $products,
        ]);
    }

    public function productDetail(string $slug): View
    {
        $product = Product::query()->with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $productGallery = $this->resolveProductGallery($product);

        $recommendations = Product::query()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(3)
            ->get();

        return view('pages.product-detail', [
            'product' => $product,
            'productGallery' => $productGallery,
            'recommendations' => $recommendations->map(fn (Product $item) => $this->mapProductCard($item))->all(),
        ]);
    }

<<<<<<< HEAD
    private function resolveProductGallery(Product $product): array
    {
        $cacheKey = 'sr12_gallery_'.$product->slug;

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($product): array {
            $base = 'https://sr12herbalskincare.co.id/minio/websr12/';
            $urls = [];

            try {
                $response = Http::timeout(15)
                    ->acceptJson()
                    ->get('https://sr12herbalskincare.co.id/api/product/detail/'.$product->slug);

                if ($response->ok()) {
                    $data = $response->json('data');
                    if (is_array($data)) {
                        $mainThumbnail = trim((string) ($data['thumbnail'] ?? ''));
                        if ($mainThumbnail !== '') {
                            $urls[] = $base.$mainThumbnail;
                        }

                        foreach (($data['productAssets'] ?? []) as $asset) {
                            $assetThumb = trim((string) ($asset['thumbnail'] ?? ''));
                            if ($assetThumb !== '') {
                                $urls[] = $base.$assetThumb;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Fallback to local image if remote API is not reachable.
            }

            if (! empty($product->image_url)) {
                $urls[] = $product->image_url;
            }

            $gallery = array_values(array_unique(array_filter($urls, fn ($url) => is_string($url) && $url !== '')));

            if (empty($gallery)) {
                return [];
            }

            while (count($gallery) < 3) {
                $gallery[] = $gallery[0];
            }

            return array_slice($gallery, 0, 3);
        });
=======
    private function featuredProducts(): Collection
    {
        $mode = AppSetting::getValue('featured_products_mode', 'default');
        $selectedIds = collect(explode(',', AppSetting::getValue('featured_product_ids')))
            ->map(fn (string $id) => (int) trim($id))
            ->filter()
            ->unique()
            ->take(4)
            ->values();

        if ($mode === 'manual' && $selectedIds->isNotEmpty()) {
            $manualProducts = Product::query()
                ->where('is_active', true)
                ->whereIn('id', $selectedIds->all())
                ->get()
                ->sortBy(fn (Product $product) => $selectedIds->search($product->id))
                ->values();

            if ($manualProducts->isNotEmpty()) {
                return $manualProducts;
            }
        }

        return Product::query()
            ->where('is_active', true)
            ->orderByDesc('rating')
            ->limit(4)
            ->get();
    }

    private function landingSettings(): array
    {
        return [
            'hero_badge' => AppSetting::getValue('landing_hero_badge', 'Distributor Resmi SR12 Herbal Skincare'),
            'hero_title' => AppSetting::getValue('landing_hero_title', 'beauty is not a dream'),
            'hero_highlight' => AppSetting::getValue('landing_hero_highlight', 'bringing back your beauty'),
            'hero_description' => AppSetting::getValue('landing_hero_description', 'Temukan rahasia kulit sehat dan bercahaya dengan rangkaian produk SR12 yang telah teruji secara dermatologis dan bersertifikat BPOM.'),
            'primary_button_text' => AppSetting::getValue('landing_primary_button_text', 'Mulai Belanja Sekarang'),
            'secondary_button_text' => AppSetting::getValue('landing_secondary_button_text', 'Lihat Katalog'),
            'hero_image' => AppSetting::getValue('landing_hero_image', asset('images/landing/hero-sr12-catalogue.jpeg')),
            'cta_title' => AppSetting::getValue('landing_cta_title', 'Bergabunglah Dengan Ribuan Reseller & Konsumen Loyal SR12 Parungpanjang'),
            'cta_description' => AppSetting::getValue('landing_cta_description', 'Dapatkan informasi promo eksklusif, tips kecantikan harian, dan penawaran khusus langsung di genggaman Anda.'),
            'cta_primary_button_text' => AppSetting::getValue('landing_cta_primary_button_text', 'Daftar Sekarang'),
            'cta_secondary_button_text' => AppSetting::getValue('landing_cta_secondary_button_text', 'Pelajari Produk'),
        ];
>>>>>>> 4b832faa2a6d3640b15799e16f0352fbb3aba2dd
    }

    private function mapProductCard(Product $product): array
    {
        return [
            'name' => $product->name,
            'category' => $product->category?->name ?? 'Skincare',
            'price' => (int) $product->price,
            'rating' => (float) $product->rating,
            'stock' => (int) $product->stock,
            'stock_status' => $product->stockStatus(),
            'slug' => $product->slug,
            'image' => $product->image_url,
            'description' => $product->description ?? '',
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Carbon\Carbon;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // جلب الأقسام من قاعدة البيانات بالـ slug (أكيد ومضمون)
        $categories = Category::pluck('id', 'slug');

        $honeyTypes = [
            [
                'name'          => 'السدر',
                'category_slug' => 'aasl-alsdr',
                'slug_key'      => 'alsdr',
                'prices'        => [350, 175, 87],
            ],
            [
                'name'          => 'الطلح',
                'category_slug' => 'aasl-altlh',
                'slug_key'      => 'altalh',
                'prices'        => [350, 175, 87],
            ],
            [
                'name'          => 'السمرة',
                'category_slug' => 'aasl-alsmr',
                'slug_key'      => 'alsmra',
                'prices'        => [250, 125, 62.5],
            ],
            [
                'name'          => 'السلام',
                'category_slug' => 'aasl-alslam',
                'slug_key'      => 'aslam',
                'prices'        => [300, 150, 75],
            ],
            [
                'name'          => 'الضهيانة',
                'category_slug' => 'aasl-aldhyan',
                'slug_key'      => 'aldhyan',
                'prices'        => [250, 125, 62.5],
            ],
            [
                'name'          => 'المجرى',
                'category_slug' => 'aasl-almgr',
                'slug_key'      => 'almjra',
                'prices'        => [800, 400, 200],
            ],
        ];

        $sizes = [
            ['label' => 'كيلو',     'weight' => '1 كيلو',   'suffix' => 'kilo'],
            ['label' => 'نص كيلو',  'weight' => 'نص كيلو',  'suffix' => 'half'],
            ['label' => 'ربع كيلو', 'weight' => 'ربع كيلو', 'suffix' => 'quarter'],
        ];

        $products = [];

        foreach ($honeyTypes as $honey) {
            $categoryId = $categories[$honey['category_slug']] ?? null;

            if (! $categoryId) {
                $this->command->warn("⚠️  القسم '{$honey['category_slug']}' غير موجود - تم تخطي منتجات {$honey['name']}");
                continue;
            }

            foreach ($sizes as $index => $size) {
                $products[] = [
                    'name'        => "عسل {$honey['name']} - {$size['label']}",
                    'slug'        => "{$honey['slug_key']}-{$size['suffix']}",
                    'description' => null,
                    'price'       => $honey['prices'][$index],
                    'stock'       => 100,
                    'weight'      => $size['weight'],
                    'image_url'   => null,
                    'category_id' => $categoryId,
                    'is_active'   => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        DB::table('products')->insert($products);

        $this->command->info("✅ تم إضافة " . count($products) . " منتج بنجاح!");
    }
}

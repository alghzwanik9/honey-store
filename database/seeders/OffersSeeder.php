<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class OffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء أو استدعاء قسم العروض
        $category = Category::firstOrCreate(
            ['slug' => 'offers'],
            [
                'name' => 'العروض',
                'description' => 'أفضل العروض والبكجات الحصرية'
            ]
        );
        $categoryId = $category->id;

        $products = [
            ['name' => 'نص سدر + نص سمره + هدية عسل زهور', 'price' => 300, 'weight' => '1 كيلو'],
            ['name' => 'نص سدر + نص طلح + هدية عسل زهور', 'price' => 320, 'weight' => '1 كيلو'],
            ['name' => 'نص سدر + نص سلام + هدية عسل زهور', 'price' => 300, 'weight' => '1 كيلو'],
            ['name' => 'نص سدر + نص ضهيانة + هدية عسل زهور', 'price' => 280, 'weight' => '1 كيلو'],

            ['name' => 'كيلو سدر + كيلو سمره + هدية عسل زهور', 'price' => 500, 'weight' => '2 كيلو'],
            ['name' => 'كيلو سدر + كيلو طلح + هدية عسل زهور', 'price' => 600, 'weight' => '2 كيلو'],
            ['name' => 'كيلو سدر + كيلو سلام + هدية عسل زهور', 'price' => 550, 'weight' => '2 كيلو'],
            ['name' => 'كيلو سدر + كيلو ضهيانة + هدية عسل زهور', 'price' => 500, 'weight' => '2 كيلو'],

            ['name' => 'ربع سدر + ربع سمره + هدية عسل زهور', 'price' => 150, 'weight' => 'نصف كيلو'],
            ['name' => 'ربع سدر + ربع طلح + هدية عسل زهور', 'price' => 165, 'weight' => 'نصف كيلو'],
            ['name' => 'ربع سدر + ربع سلام + هدية عسل زهور', 'price' => 150, 'weight' => 'نصف كيلو'],
            ['name' => 'ربع سدر + ربع ضهيانة + هدية عسل زهور', 'price' => 150, 'weight' => 'نصف كيلو'],

            ['name' => 'نص طلح + نص سمره + نص سلام', 'price' => 400, 'weight' => '1.5 كيلو'],
            ['name' => 'نص سدر + نص طلح + نص سمره', 'price' => 400, 'weight' => '1.5 كيلو'],
            ['name' => 'نص سدر + نص سلام + نص سمرة', 'price' => 400, 'weight' => '1.5 كيلو'],
            ['name' => 'نص سدر + نص ضهيانة + نص سمرة', 'price' => 400, 'weight' => '1.5 كيلو'],
    ];

        $created = 0;
        foreach ($products as $p) {
            $slug = Str::slug($p['name']) . '-' . rand(1000, 9999);
            Product::firstOrCreate(
                ['name' => $p['name']],
                [
                    'slug' => $slug,
                    'price' => $p['price'],
                    'weight' => $p['weight'],
                    'category_id' => $categoryId,
                    'stock' => 50,
                    'is_active' => true,
                    'description' => '<p><strong>' . $p['name'] . '</strong> وفرناها لكم بجودة ممتازة لتقديم أفضل عسل طبيعي.</p>'
                ]
            );
            $created++;
        }

        $this->command->info("تمت إضافة {$created} بكج في قسم العروض بنجاح! 🍯");
    }
}

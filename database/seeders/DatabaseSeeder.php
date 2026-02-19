<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        // Check if user exists first to avoid duplicate email error
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'), // Or default
                'is_admin' => true,
            ]);
        }

        // Categories
        $honeyC = Category::firstOrCreate(['slug' => 'honey'], ['name' => 'عسل طبيعي']);
        $accessoriesC = Category::firstOrCreate(['slug' => 'accessories'], ['name' => 'مستلزمات العسل']);
        $giftsC = Category::firstOrCreate(['slug' => 'gifts'], ['name' => 'هدايا وباقات']);

        // Products
        // 1. Sidr Honey
        Product::updateOrCreate(['slug' => 'sidr-honey'], [
            'name' => 'عسل سدر فاخر',
            'description' => 'عسل سدر جبلي أصلي، مذاق فريد وجودة عالية.',
            'price' => 120.00,
            'stock' => 50,
            'is_active' => true,
            'category_id' => $honeyC->id,
            // 'image' => 'products/sidr.jpg' // Assuming image handling
        ]);

        // 2. Wild Flower Honey
        Product::updateOrCreate(['slug' => 'flower-honey'], [
            'name' => 'عسل زهور برية',
            'description' => 'مزيج رائع من رحيق الزهور البرية في موسم الربيع.',
            'price' => 85.00,
            'stock' => 30,
            'is_active' => true,
            'category_id' => $honeyC->id,
        ]);

        // 3. Black Forest Honey
        Product::updateOrCreate(['slug' => 'black-forest-honey'], [
            'name' => 'عسل الغابة السوداء',
            'description' => 'عسل داكن ذو قوام كثيف وطعم قوي ومميز.',
            'price' => 150.00,
            'stock' => 15,
            'is_active' => true,
            'category_id' => $honeyC->id,
        ]);

        // 4. Honey Spoon (Accessory)
        Product::updateOrCreate(['slug' => 'honey-spoon'], [
            'name' => 'ملعقة عسل خشبية',
            'description' => 'ملعقة مصنوعة من خشب الزيتون الطبيعي.',
            'price' => 15.00,
            'stock' => 100,
            'is_active' => true,
            'category_id' => $accessoriesC->id,
        ]);

        // 5. Gift Box
        Product::updateOrCreate(['slug' => 'royal-gift-box'], [
            'name' => 'صندوق الهدايا الملكي',
            'description' => 'تشكيلة فاخرة من أفضل أنواع العسل مناسبة للإهداء.',
            'price' => 350.00,
            'stock' => 5,
            'is_active' => true,
            'category_id' => $giftsC->id,
        ]);

        // Call the specific honey categories seeder
        $this->call(HoneyCategoriesSeeder::class);
    }
}

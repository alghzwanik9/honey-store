<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class HoneyStoreSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $sidr = Category::create(['name' => 'عسل السدر', 'slug' => 'sidr-honey']);
        $talh = Category::create(['name' => 'عسل الطلح', 'slug' => 'talh-honey']);
        $flower = Category::create(['name' => 'عسل الزهور', 'slug' => 'flower-honey']);
        $acacia = Category::create(['name' => 'عسل الأكاسيا', 'slug' => 'acacia-honey']);

        // Sidr Products
        Product::create([
            'category_id' => $sidr->id,
            'name' => 'عسل سدر ملكي',
            'slug' => 'royal-sidr-honey',
            'description' => 'عسل سدر ملكي فاخر من أودية دوعن. معروف بطعمه الغني وفوائده العلاجية.',
            'price' => 150.00,
            'stock' => 50,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $sidr->id,
            'name' => 'عسل سدر جبلي',
            'slug' => 'mountain-sidr-honey',
            'description' => 'عسل سدر جبلي بري، تم حصاده من مناطق نائية.',
            'price' => 120.00,
            'stock' => 30,
            'is_active' => true,
        ]);

        // Talh Products
        Product::create([
            'category_id' => $talh->id,
            'name' => 'عسل طلح أسود',
            'slug' => 'black-talh-honey',
            'description' => 'عسل طلح أسود أصلي بنكهة قوية ومميزة.',
            'price' => 85.00,
            'stock' => 100,
            'is_active' => true,
        ]);

        // Flower Products
        Product::create([
            'category_id' => $flower->id,
            'name' => 'عسل زهور برية',
            'slug' => 'wildflower-honey',
            'description' => 'مزيج من رحيق الزهور البرية المتنوعة. طعم حلو وزهري.',
            'price' => 45.00,
            'stock' => 200,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $flower->id,
            'name' => 'عسل اللافندر',
            'slug' => 'lavender-honey',
            'description' => 'عسل رقيق مع نفحة من رائحة اللافندر.',
            'price' => 55.00,
            'stock' => 80,
            'is_active' => true,
        ]);

        // Acacia Products
        Product::create([
            'category_id' => $acacia->id,
            'name' => 'عسل أكاسيا صافي',
            'slug' => 'pure-acacia-honey',
            'description' => 'عسل أكاسيا صافي وخفيف وحلو جداً. ومن خصائصه أنه يبقى سائلاً لفترة طويلة.',
            'price' => 60.00,
            'stock' => 150,
            'is_active' => true,
        ]);
    }
}

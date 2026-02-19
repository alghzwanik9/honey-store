<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class HoneyCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'سدر جبلي', 'slug' => 'sidr-mountain'],
            ['name' => 'مورينقا', 'slug' => 'moringa'],
            ['name' => 'سلم', 'slug' => 'salam'],
            ['name' => 'سمرة', 'slug' => 'samra'],
            ['name' => 'طلح', 'slug' => 'talh'],
            ['name' => 'ضهيانة', 'slug' => 'dhayana'],
            ['name' => 'قتاد', 'slug' => 'qatad'],
            ['name' => 'مجرى', 'slug' => 'majra'],
        ];

        // Ensure parent 'Honey' category exists or treat them as top level?
        // For now, creating them as top-level categories for the dropdown.
        // If we want a parent, we would need a parent_id column, but simplicity suggests flat for now.

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
    }
}

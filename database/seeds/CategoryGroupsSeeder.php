<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class CategoryGroupsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_groups')->insert([
            [
                'id' => 1,
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Cookware, Dining, Bath, Home Decor and more',
                'icon' => 'fa-shower',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 2,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Mobile, Computer, Tablet, Camera etc',
                'icon' => 'fa-plug',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 3,
                'name' => 'Kids and Toy',
                'slug' => 'kids-toy',
                'description' => 'Toys, Footwear etc',
                'icon' => 'fa-gamepad',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 4,
                'name' => 'Clothing and Shoes',
                'slug' => 'clothing-shoes',
                'description' => 'Shoes, Clothing, Life style items',
                'icon' => 'fa-tshirt',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 5,
                'name' => 'Beauty and Health',
                'slug' => 'beauty-health',
                'description' => 'Cosmetics, Foods and more.',
                'icon' => 'fa-hot-tub',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 6,
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Cycle, Tennis, Boxing, Cricket and more.',
                'icon' => 'fa-skiing',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 7,
                'name' => 'Jewelry',
                'slug' => 'jewelry',
                'description' => 'Necklances, Rings, Pendants and more.',
                'icon' => 'fa-gem',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 8,
                'name' => 'Pets',
                'slug' => 'pets',
                'description' => 'Pet foods and supplies.',
                'icon' => 'fa-dog',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'id' => 9,
                'name' => 'Hobbies & DIY',
                'slug' => 'hobbies-diy',
                'description' => 'Craft Sewing, Supplies and more.',
                'icon' => 'fa-paint-brush',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ]
        ]);

        if (File::isDirectory($this->demo_dir)) {
            $category_groups = \DB::table('category_groups')->pluck('id')->toArray();
            $data = [];

            foreach ($category_groups as $grp) {
                $img = $this->demo_dir . "/categories/{$grp}.png";
                if (!file_exists($img)) continue;

                $name = "category_{$grp}.png";
                $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                if ($this->disk->put($targetFile, file_get_contents($img))) {
                    $data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => 'png',
                        'featured' => 0,
                        'type' => 'background',
                        'imageable_id' => $grp,
                        'imageable_type' => 'App\CategoryGroup',
                        'created_at' => Carbon::Now(),
                        'updated_at' => Carbon::Now(),
                    ];
                }
            }

            DB::table('images')->insert($data);
        }
    }
}

<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductsSeeder extends BaseSeeder
{

    private $longCount = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Product::class, $this->longCount)->create();

        if (File::isDirectory($this->demo_dir)) {
            $products = \DB::table('products')->pluck('id')->toArray();

            $img_dirs = glob($this->demo_dir . '/products/*', GLOB_ONLYDIR);

            foreach ($products as $item) {
                $images = glob($img_dirs[array_rand($img_dirs)] . DIRECTORY_SEPARATOR . '*.{jpg,png,jpeg}', GLOB_BRACE);
                $i = 1;
                foreach ($images as $file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $name = Str::random(10) . '.' . $ext;
                    $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                    if ($this->disk->put($targetFile, file_get_contents($file))) {
                        DB::table('images')->insert([
                            [
                                'name' => $name,
                                'path' => $targetFile,
                                'extension' => $ext,
                                'size' => filesize($file),
                                'featured' => ($i == 1),
                                'imageable_id' => $item,
                                'imageable_type' => 'App\Product',
                                'created_at' => Carbon::Now(),
                                'updated_at' => Carbon::Now(),
                            ]
                        ]);
                    }
                    $i++;
                }
            }
        }
    }
}

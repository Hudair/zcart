<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class InventoriesSeeder extends BaseSeeder
{

    private $itemCount = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventory::class, $this->itemCount)->create();

        if (File::isDirectory($this->demo_dir)) {
            $data = [];
            $inventories = \DB::table('inventories')->pluck('id')->toArray();
            $img_dirs = glob($this->demo_dir . '/products/*', GLOB_ONLYDIR);

            foreach ($inventories as $item) {
                $images = glob($img_dirs[array_rand($img_dirs)] . DIRECTORY_SEPARATOR . '*.{jpg,png,jpeg}', GLOB_BRACE);

                foreach ($images as $file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $name = Str::random(10) . '.' . $ext;
                    $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                    if ($this->disk->put($targetFile, file_get_contents($file))) {
                        $data[] = [
                            'name' => $name,
                            'path' => $targetFile,
                            'extension' => $ext,
                            'size' => filesize($file),
                            'imageable_id' => $item,
                            'imageable_type' => 'App\Inventory',
                            'created_at' => Carbon::Now(),
                            'updated_at' => Carbon::Now(),
                        ];
                    }
                }
            }

            \DB::table('images')->insert($data);
        }
    }
}

<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BannersSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Banner::class, 3)->create([
            'columns' => 4,
            'group_id' => 'group_1'
        ]);
        factory(App\Banner::class, 2)->create([
            'columns' => 6,
            'group_id' => 'group_2'
        ]);
        // factory(App\Banner::class)->create([
        //     'columns' => 12,
        //     'group_id' => 'group_3'
        // ]);
        factory(App\Banner::class, 2)->create([
            'columns' => 6,
            'group_id' => 'group_4'
        ]);
        factory(App\Banner::class)->create([
            'columns' => 12,
            'group_id' => 'group_5'
        ]);
        factory(App\Banner::class, 2)->create([
            'columns' => 6,
            'group_id' => 'group_6'
        ]);

        if (File::isDirectory($this->demo_dir)) {
            // $images = glob($this->demo_dir . '/banners/backgrounds/*.{jpg,png,jpeg}', GLOB_BRACE);
            $images = glob($this->demo_dir . '/banners/*.{jpg,png,jpeg}', GLOB_BRACE);
            natsort($images);
            $i = 0;
            $data = [];

            foreach ($images as $file) {
                $i++;
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = Str::random(10) . '.' . $ext;
                $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'featured' => 1,
                        'type' => 'feature',
                        'imageable_id' => $i,
                        'imageable_type' => 'App\Banner',
                        'created_at' => Carbon::Now(),
                        'updated_at' => Carbon::Now(),
                    ];
                }

                // $hover = $this->demo_dir . "/banners/hover/{$i}.png";

                // if(! file_exists($hover)) continue;

                // $name = "banner_{$i}.png";
                // $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                // if($this->disk->put($targetFile, file_get_contents($hover))) {
                //     DB::table('images')->insert([
                //         'name' => $name,
                //         'path' => $targetFile,
                //         'extension' => 'png',
                //         'featured' => 1,
                //         'imageable_id' => $i,
                //         'imageable_type' => 'App\Banner',
                //         'created_at' => Carbon::Now(),
                //         'updated_at' => Carbon::Now(),
                //     ]);
                // }
            }

            DB::table('images')->insert($data);
        }
    }
}

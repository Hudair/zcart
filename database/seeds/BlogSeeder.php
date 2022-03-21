<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class BlogSeeder extends BaseSeeder
{

    private $count = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Blog::class, $this->count)->create();

        if (File::isDirectory($this->demo_dir)) {
            $blogs = DB::table('blogs')->pluck('id')->toArray();

            foreach ($blogs as $blog) {
                $img = $this->demo_dir . "/blogs/{$blog}.png";
                if (!file_exists($img)) continue;

                $name = "blog_{$blog}.png";
                $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                if ($this->disk->put($targetFile, file_get_contents($img))) {
                    DB::table('images')->insert([
                        [
                            'name' => $name,
                            'path' => $targetFile,
                            'extension' => 'png',
                            'featured' => 1,
                            'type' => 'cover',
                            'imageable_id' => $blog,
                            'imageable_type' => 'App\Blog',
                            'created_at' => Carbon::Now(),
                            'updated_at' => Carbon::Now(),
                        ]
                    ]);
                }
            }
        }
    }
}

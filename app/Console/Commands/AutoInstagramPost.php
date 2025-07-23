<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Post;

class AutoInstagramPost extends Command
{
    protected $signature = 'auto:instagram-post';
    protected $description = 'Automatically create a post every minute based on day of week';

    public function handle()
    {
        $today = now()->format('Y-m-d H:i'); // Prevent repost in same minute
        $logFile = storage_path('app/last_auto_post.txt');

        // if (file_exists($logFile)) {
        //     $last = trim(file_get_contents($logFile));
        //     if ($last === $today) {
        //         $this->info('✅ Already posted this minute. Skipping.');
        //         return;
        //     }
        // }

        $weekday = now()->format('l');
        $quote = config("autoquotes.$weekday");
        $imagePath = "auto_posts/$weekday.jpg";

        if (!Storage::exists($imagePath)) {
            $this->error("❌ Image for $weekday not found.");
            return;
        }

        // Save to public/upload/post
        $filename = $weekday . '.jpg';
        $publicPath = public_path('upload/post/' . $filename);
        File::copy(Storage::path($imagePath), $publicPath);

        // Create post
        Post::create([
            'user_id' => 1, // bot user ID
            'description' => $quote,
            'image_name' => 'upload/post/'. $filename,
            'location' => 'Auto Bot',
            'is_auto' => true
        ]);

        file_put_contents($logFile, $today);
        $this->info("✅ Post created at $today");
    }
}

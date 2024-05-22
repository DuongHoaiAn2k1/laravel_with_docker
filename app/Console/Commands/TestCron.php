<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $message = "Cron job 'app:test-cron' executed successfully at " . now();
        // Log::info($message); // Ghi thông báo vào file log của Laravel
        $title = 'Post created at ' . now()->toDateTimeString();
        $content = 'This post was created by the cron job.';

        $post = new Post();
        $post->title = $title;
        $post->content = $content;
        $post->save();
    }
}

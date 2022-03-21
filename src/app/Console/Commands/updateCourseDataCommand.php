<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateCourseDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateCourseDataCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '楽天GORAでのゴルフコース取得API実行。ゴルフコースデータを最新状態にする';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        exec('php artisan db:seed --class=CorseSeeder');
    }
}

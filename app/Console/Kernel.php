<?php

namespace App\Console;

use App\Console\HotVideo\VideoCoverCommand;
use App\Console\Subject\CoverCommand;
use App\Console\Subject\DetailCommand;
use App\Console\Subject\LeaguesJsonCommand;
use App\Console\Subject\PlayerCommand;
use App\Console\HotVideo\VideoPageCommand;
use App\Console\SubjectVideo\MobileSubjectVideoPageCommand;
use App\Console\SubjectVideo\SubjectVideoCoverCommand;
use App\Console\SubjectVideo\SubjectVideoDetailCommand;
use App\Console\SubjectVideo\SubjectVideoPageCommand;
use App\Http\Controllers\Mobile\Live\LiveController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        IndexCommand::class,//直播 列表静态化
        LiveDetailCommand::class,//PC终端、移动终端html缓存。
        LivesJsonCommand::class,//列表json静态化
        SpiderCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('live_json_cache:run')->everyMinute();//每分钟刷新一次赛事缓存
        $schedule->command('index_cache:run')->everyMinute();//每分钟刷新主页缓存
        $schedule->command('live_detail_cache:run')->everyFiveMinutes();//每5分钟刷新终端缓存

        $schedule->command('spider:run lqb_article_list')->hourlyAt(5);
        $schedule->command('spider:run lqb_articles')->hourlyAt(35);
        $schedule->command('spider:run sync_articles')->hourlyAt(45);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

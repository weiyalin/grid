<?php
/**
 * Created by PhpStorm.
 * User: wangzhiyuan
 * Date: 15/12/24
 * Time: 上午11:45
 */
namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class SyncTask extends Command{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步在线用户';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Task::sync_online();

    }


}

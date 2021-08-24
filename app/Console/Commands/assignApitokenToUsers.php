<?php

namespace App\Console\Commands;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class assignApitokenToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignApitokenToUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = User::get('id');
        foreach ($users as $user) {
            $apitoken = bin2hex(random_bytes(16));
            User::where('id',$user->id)->update(['apitoken'=>$apitoken]);
        }
    }
}

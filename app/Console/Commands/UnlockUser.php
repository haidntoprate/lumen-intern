<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class UnlockUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'unlock user';

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
        $user = User::where('status', '=', 2)->get();
        
        $user->each(function ($item) {
            if ($item->lock_time <= Carbon::now('Asia/Ho_Chi_Minh')) {
                $item->status = 1;
                $item->save();
            }
        });
        // $user = new User();
        // $user->name = 'sae jan';
        // $user->email = 'sae jan';
        // $user->password = 'sae jan';
        // $user->avatar = 'sae jan';
        // $user->save();
    }
}

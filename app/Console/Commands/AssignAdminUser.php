<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:assignadminuser {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Příkaz pro přidělení práv admina';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $username = $this->argument('user');
        if($username){
            $user = User::where("username", $username)->firstOrFail();
            $user->givePermissionTo("admin");
            return Command::SUCCESS;
        }else{
            return Command::INVALID;
        }
    }
}

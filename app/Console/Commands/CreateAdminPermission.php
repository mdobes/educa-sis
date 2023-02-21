<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class CreateAdminPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createadminpermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Příkaz pro vytvoření admin práv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Permission::findOrCreate("admin");
        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RehashAllPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-passwords {--password= : The new plain password to set for all users} {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all users passwords to a given plain password (hashed with bcrypt)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $plain = $this->option('password') ?? '1345678';

        if (! $this->option('force')) {
            $this->info('This will set every user password to: ' . $plain);
            if (! $this->confirm('Are you sure you want to continue?')) {
                $this->info('Aborted.');
                return 1;
            }
        }

        $bar = $this->output->createProgressBar(User::count());
        $bar->start();

        User::chunk(100, function ($users) use ($plain, $bar) {
            foreach ($users as $user) {
                $user->password = Hash::make($plain);
                $user->save();
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('All user passwords updated.');

        return 0;
    }
}

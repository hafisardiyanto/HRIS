<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DataKaryawan;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test {--username= : Username} {--email= : Email} {--password= : Password} {--role=Employee : Role (Administrator or Employee)} {--nama= : Full name} {--alamat= : Alamat} {--telepon= : Phone number} {--status=Karyawan Tetap : Status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user with Bcrypt hashed password and employee data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $username = $this->option('username') ?? $this->ask('Username');
        $email = $this->option('email') ?? $this->ask('Email');
        $password = $this->option('password') ?? $this->secret('Password');
        $role = $this->option('role');
        $nama = $this->option('nama') ?? $this->ask('Full name');
        $alamat = $this->option('alamat') ?? $this->ask('Alamat');
        $telepon = $this->option('telepon') ?? $this->ask('Phone number');
        $status = $this->option('status');

        // Check if user already exists
        if (User::where('username', $username)->orWhere('email', $email)->exists()) {
            $this->error('User with this username or email already exists!');
            return 1;
        }

        // Create user
        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        // Create employee data
        DataKaryawan::create([
            'nama' => $nama,
            'alamat' => $alamat,
            'nomor_telepon' => $telepon,
            'status_karyawan' => $status,
            'keahlian' => 'General',
            'jabatan' => $role === 'Administrator' ? 'Admin' : 'Employee',
            'user_id' => $user->id_user,
        ]);

        $this->info("User created successfully!");
        $this->info("Username: {$user->username}");
        $this->info("Email: {$user->email}");
        $this->info("Name: {$nama}");
        $this->info("Role: {$user->role}");

        return 0;
    }
}

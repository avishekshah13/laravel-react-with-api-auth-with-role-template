<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserWithRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@doc.com',
            'password' => 'Hello@123'
        ]);
        
        $user = User::where('email', 'admin@doc.com')->first();
        $user->assignRole('admin');
    }
}

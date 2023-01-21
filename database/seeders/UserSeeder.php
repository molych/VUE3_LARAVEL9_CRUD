<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin')
        ]);
        $admin->roles()->attach(Role::where('name', 'Administrator')->value('id'));

        $editor = User::factory()->create([
            'name' => 'editor',
            'email' => 'editor@mail.com',
            'password' => Hash::make('editor')
        ]);
        $editor->roles()->attach(Role::where('name', 'Editor')->value('id'));
    }
}

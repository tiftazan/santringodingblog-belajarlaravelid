<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Santri Ngoding',
            'username' => 'santringoding',
            'email' => 'santringoding@gmail.com',
            'password' => Hash::make('password212'),
        ]);
        User::factory(5)->create();
    }
}

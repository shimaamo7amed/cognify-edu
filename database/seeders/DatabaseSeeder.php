<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin =Admin::create([
            'code' => 'ADM001',
            'name' => 'Cognify-Admin',
            'email' => "cognify@admin.com",
            'password' => bcrypt('Cognify@admin'),
        ]);
        $admin->assignRole('admin');


        // $admin =Admin::create([
        //     'code' => 'ADM002',
        //     'name' => 'Cognify-SuperAdmin',
        //     'email' => "cognifysuper_admin@admin.com",
        //     'password' => bcrypt('CognifySuper@admin'),
        // ]);
        // $admin->assignRole('superAdmin');


    }
}

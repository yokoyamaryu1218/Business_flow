<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            TaskSeeder::class,
            DocumentsSeeder::class,
            ProcedureSeeder::class,
            ProcedureDocumentSeeder::class,
            RoutineSeeder::class,
            DocumentHistorySeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();
    }
}

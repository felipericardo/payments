<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'name'       => 'Felipe Ricardo do Nascimento',
                'document'   => '07168931938',
                'email'      => 'contato@felipericardo.com',
                'password'   => Hash::make('password'),
                'type'       => 'CUSTOMER',
                'balance'    => 10000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        DB::table('users')->insert(
            [
                'name'       => 'Apple Computer Brasil Ltda.',
                'document'   => '00623904000173',
                'email'      => 'contato@apple.com.br',
                'password'   => Hash::make('password'),
                'type'       => 'STORE',
                'balance'    => 100000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}

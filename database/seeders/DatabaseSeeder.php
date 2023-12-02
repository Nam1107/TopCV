<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
Use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Role_tb::insert([
            [ 'role_name' => 'user'],
            [ 'role_name' => 'manager'],
            [ 'role_name' => 'admin'],
            [ 'role_name' => 'coder'],
        ]);
        

        \App\Models\User::create(
            [
                'name' => "Tran Nam",
                'sex' => "Nam",
                'phone'=> "0972969136",
                'address'=> "Mỗ Lao",
                'province'=> "Hà Nội",
                'district'=> "Ha Đông",
                'avatar'=>'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.nj.com%2Fentertainment%2F2020%2F05%2Feveryones-posting-their-facebook-avatar-how-to-make-yours-even-if-it-looks-nothing-like-you.html&psig=AOvVaw0ORbck3Xz-oUKq6-Alu4ux&ust=1700070234042000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCKDIiKmFxIIDFQAAAAAdAAAAABAE',
                'email' => "nam@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make(12345678),//'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                // 'remember_token' => Str::random(10),
            ]
        );

        $this->call(UserSeeder::class);

        DB::statement('
            INSERT INTO role_user(role_id,user_id)
            SELECT 1,id FROM users
            WHERE users.id >1
        ');
        \App\Models\Role_user::insert(
            [
                [
                    "role_id"=>1,
                    "user_id"=>1
                ],[
                    "role_id"=>2,
                    "user_id"=>1
                ],
                [
                    "role_id"=>3,
                    "user_id"=>1
                ],[
                    "role_id"=>4,
                    "user_id"=>1
                ]
            ]
        );

        $this->call(CompanySeeder::class);

    }
}
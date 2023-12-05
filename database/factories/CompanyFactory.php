<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            // 'name',
            // 'email',
            // 'address',
            // 'district',
            // 'city',
            // 'phone',
            // 'logo',
            // 'detail',
            // 'url_page',
            // 'owner_id',
            // 'follow_count',
            'name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'district' => fake()->city(),
            'province' => fake()->state(),
            'phone' => fake()->phoneNumber(),
            'logo' => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.nj.com%2Fentertainment%2F2020%2F05%2Feveryones-posting-their-facebook-avatar-how-to-make-yours-even-if-it-looks-nothing-like-you.html&psig=AOvVaw2-376iRzH25-rAHpAcQ5Lq&ust=1699981162208000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCOi-3L-5wYIDFQAAAAAdAAAAABAE',
            'detail' => " Trong suốt 29 năm phát triển, Công ty Hệ thống Thông tin FPT (FPT Information System - FPT IS) là nhà tích hợp hệ thống, cung cấp giải pháp hàng đầu Việt Nam và khu vực. Sở hữu năng lực công nghệ được thừa nhận bởi các khách hàng và đối tác toàn cầu, FPT IS mang đến những dịch vụ và giải pháp phục vụ các lĩnh vực trọng...",
            'url_page' => "https://www.fis.com.vn/",
            'manager_id' => \App\Models\User::all()->random()->id,
            'follow_count' => 1
            
        ];
    }
}

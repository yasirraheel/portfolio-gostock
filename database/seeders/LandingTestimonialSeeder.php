<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingTestimonial;

class LandingTestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'John Smith',
                'client_position' => 'Software Developer',
                'testimonial_text' => 'This platform helped me land my dream job! The portfolio looked so professional that HR was impressed from the first glance.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'client_name' => 'Maria Johnson',
                'client_position' => 'UX Designer',
                'testimonial_text' => 'The private portfolio feature is amazing. I can share my work with specific companies without making it public.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'client_name' => 'David Rodriguez',
                'client_position' => 'Marketing Manager',
                'testimonial_text' => 'Free and easy to use! I created my portfolio in 30 minutes and got 3 job interviews the same week.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 3
            ],
            [
                'client_name' => 'Sarah Wilson',
                'client_position' => 'Frontend Developer',
                'testimonial_text' => 'The mobile responsiveness is perfect. My portfolio looks great on all devices and helped me stand out from other candidates.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 4
            ],
            [
                'client_name' => 'Michael Chen',
                'client_position' => 'Product Manager',
                'testimonial_text' => 'The contact integration feature is brilliant. HR professionals can reach out directly through my portfolio, making the hiring process so much smoother.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 5
            ]
        ];

        foreach ($testimonials as $testimonial) {
            LandingTestimonial::create($testimonial);
        }
    }
}
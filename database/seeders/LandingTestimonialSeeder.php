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
        // Clear existing testimonials
        LandingTestimonial::truncate();

        $testimonials = [
            [
                'client_name' => 'Ahmad Ali',
                'client_position' => 'Software Engineer',
                'testimonial_text' => 'Bhai, yeh platform bilkul amazing hai! Mera portfolio banaya aur 2 hafte mein hi job mil gayi. HR wale bhi impressed the.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'client_name' => 'Fatima Khan',
                'client_position' => 'UI/UX Designer',
                'testimonial_text' => 'The private portfolio feature is so good! I can share my work with specific companies without making it public. Bilkul perfect for me.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'client_name' => 'Hassan Raza',
                'client_position' => 'Marketing Manager',
                'testimonial_text' => 'Free hai aur easy bhi! 30 minutes mein portfolio banaya aur same week 3 interviews mil gaye. Mashallah!',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 3
            ],
            [
                'client_name' => 'Ayesha Malik',
                'client_position' => 'Frontend Developer',
                'testimonial_text' => 'Mobile pe bhi perfect lagta hai portfolio. HR wale ne kaha ke tumhara portfolio sabse professional laga. Very happy!',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 4
            ],
            [
                'client_name' => 'Usman Sheikh',
                'client_position' => 'Product Manager',
                'testimonial_text' => 'Contact integration feature zabardast hai. Companies directly contact kar sakte hain through portfolio. Job hunting kaafi easy ho gaya.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 5
            ],
            [
                'client_name' => 'Sana Ahmed',
                'client_position' => 'Data Analyst',
                'testimonial_text' => 'Maine apna portfolio banaya aur 1 week mein hi 2 companies se call aaya. Platform really helpful hai for job seekers.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 6
            ],
            [
                'client_name' => 'Bilal Hassan',
                'client_position' => 'Backend Developer',
                'testimonial_text' => 'Themes customize karne ka option hai, colors change kar sakte hain. Portfolio bilkul unique ban jata hai. Great work!',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 7
            ],
            [
                'client_name' => 'Zainab Ali',
                'client_position' => 'Graphic Designer',
                'testimonial_text' => 'Skills section mein progress bars lagaye hain, projects showcase kar sakte hain. Portfolio dekh ke companies impressed ho jati hain.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 8
            ],
            [
                'client_name' => 'Omar Farooq',
                'client_position' => 'DevOps Engineer',
                'testimonial_text' => 'Experience section mein achievements add kar sakte hain. HR wale ko pata chalta hai ke kya kya kiya hai. Very professional!',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 9
            ],
            [
                'client_name' => 'Nida Shah',
                'client_position' => 'Content Writer',
                'testimonial_text' => 'Bio section mein apna introduction likh sakte hain. Companies ko samajh aata hai ke tum kya karte ho. Portfolio banane mein time bhi nahi laga.',
                'rating' => 5,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 10
            ]
        ];

        foreach ($testimonials as $testimonial) {
            LandingTestimonial::create($testimonial);
        }
    }
}